<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class TwoFactorService
{
    private const PERIOD = 30;

    public function ensureColumns(): void
    {
        static $ready = false;
        if ($ready || !Schema::hasTable('users')) {
            return;
        }

        $missing = [];
        if (!Schema::hasColumn('users', 'two_factor_secret')) $missing[] = 'two_factor_secret';
        if (!Schema::hasColumn('users', 'two_factor_pending_secret')) $missing[] = 'two_factor_pending_secret';
        if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) $missing[] = 'two_factor_confirmed_at';

        if ($missing) {
            Schema::table('users', function (Blueprint $table) use ($missing) {
                if (in_array('two_factor_secret', $missing, true)) $table->text('two_factor_secret')->nullable();
                if (in_array('two_factor_pending_secret', $missing, true)) $table->text('two_factor_pending_secret')->nullable();
                if (in_array('two_factor_confirmed_at', $missing, true)) $table->dateTime('two_factor_confirmed_at')->nullable();
            });
        }

        $ready = true;
    }

    public function generateSecret(): string
    {
        return $this->base32Encode(random_bytes(20));
    }

    public function protectSecret(?string $secret): ?string
    {
        return $secret ? Crypt::encryptString($secret) : null;
    }

    public function revealSecret(?string $stored): ?string
    {
        if (!$stored) {
            return null;
        }

        try {
            return Crypt::decryptString($stored);
        } catch (\Throwable) {
            // Allow secrets created by an older plain-text implementation to be migrated on use.
            return str_starts_with($stored, 'plain:') ? substr($stored, 6) : $stored;
        }
    }

    public function verify(?string $storedSecret, ?string $code): bool
    {
        $secret = $this->revealSecret($storedSecret);
        $code = preg_replace('/\D+/', '', (string) $code);

        if (!$secret || strlen($code) !== 6) {
            return false;
        }

        $counter = intdiv(time(), self::PERIOD);
        for ($offset = -1; $offset <= 1; $offset++) {
            if (hash_equals($this->code($secret, $counter + $offset), $code)) {
                return true;
            }
        }

        return false;
    }

    public function otpAuthUri(User $user, string $secret): string
    {
        $issuer = config('app.name', 'Shishi Footsteps');
        $account = $user->email ?: 'user-'.$user->id;

        return sprintf(
            'otpauth://totp/%s:%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=%d',
            rawurlencode($issuer),
            rawurlencode($account),
            rawurlencode($secret),
            rawurlencode($issuer),
            self::PERIOD,
        );
    }

    public function qrCodeSource(string $uri): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=12&data='.rawurlencode($uri);
    }

    private function code(string $secret, int $counter): string
    {
        $key = $this->base32Decode($secret);
        $binaryCounter = pack('N*', 0).pack('N*', $counter);
        $hash = hash_hmac('sha1', $binaryCounter, $key, true);
        $offset = ord(substr($hash, -1)) & 0x0f;
        $truncated = unpack('N', substr($hash, $offset, 4))[1] & 0x7fffffff;

        return str_pad((string) ($truncated % 1000000), 6, '0', STR_PAD_LEFT);
    }

    private function base32Encode(string $binary): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bits = '';
        $output = '';

        for ($i = 0, $length = strlen($binary); $i < $length; $i++) {
            $bits .= str_pad(decbin(ord($binary[$i])), 8, '0', STR_PAD_LEFT);
        }

        for ($i = 0, $length = strlen($bits); $i < $length; $i += 5) {
            $chunk = str_pad(substr($bits, $i, 5), 5, '0');
            $output .= $alphabet[bindec($chunk)];
        }

        return $output;
    }

    private function base32Decode(string $secret): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = strtoupper(preg_replace('/[^A-Z2-7]/i', '', $secret));
        $bits = '';

        for ($i = 0, $length = strlen($secret); $i < $length; $i++) {
            $position = strpos($alphabet, $secret[$i]);
            if ($position !== false) {
                $bits .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
            }
        }

        $output = '';
        for ($i = 0, $length = strlen($bits) - 7; $i <= $length; $i += 8) {
            $output .= chr(bindec(substr($bits, $i, 8)));
        }

        return $output;
    }
}
