<?php

namespace Database\Seeders;

use App\Services\QuotationPricingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            ['Four Points by Sheraton Nairobi Airport', 'Kenya', 'Nairobi', 'preferred', 'premium', 'reservations@fourpointsnairobi.test', 24, true, true, 'Airport hotel with interconnecting family rooms.'],
            ['Tamarind Tree Hotel', 'Kenya', 'Nairobi', 'recommended', 'gold', 'reservations@tamarindtree.test', 20, true, true, 'Convenient for Wilson Airport departures.'],
            ['Mara Sentim Camp', 'Kenya', 'Maasai Mara', 'recommended', 'silver', 'bookings@marasentim.test', 22, true, true, 'Two-bedroom family rooms available; no interconnecting tents.'],
            ['Mara Simba Lodge', 'Kenya', 'Maasai Mara', 'recommended', 'gold', 'reservations@marasimba.test', 25, true, true, 'Family friendly lodge with standard rooms and river views.'],
            ['Serengeti Serena Safari Lodge', 'Tanzania', 'Serengeti', 'preferred', 'premium', 'reservations@serengetiserena.test', 28, true, true, 'Premium property with seasonal migration rates.'],
            ['Zanzibar Serena Hotel', 'Tanzania', 'Stone Town', 'normal', 'gold', 'reservations@zanzibarserena.test', 20, false, true, 'Ocean-facing rooms and presidential suite.'],
        ];

        $hotelIds = [];
        foreach ($hotels as $hotel) {
            [$name, $country, $location, $supplier, $luxury, $email, $markup, $scheme, $published, $notes] = $hotel;
            $hotelId = DB::table('hotels')->insertGetId([
                'name' => $name, 'country' => $country, 'location' => $location,
                'supplier_type' => $supplier, 'luxury_level' => $luxury,
                'reservation_email' => $email, 'phone' => '+254 700 000 000',
                'currency' => 'USD', 'default_markup_percent' => $markup,
                'notes' => $notes, 'payment_scheme_filled' => $scheme,
                'published' => $published, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $hotelIds[$name] = $hotelId;
        }

        $roomDefinitions = [
            'Four Points by Sheraton Nairobi Airport' => [
                ['Standard Room', 2, 1, 18, false, false, 185],
                ['Double Room', 2, 1, 12, false, false, 210],
                ['Family Room', 2, 3, 6, true, false, 295],
                ['Presidential Suite', 2, 2, 1, true, false, 680],
                ['Interconnecting Family Rooms', 4, 4, 3, true, true, 420],
            ],
            'Tamarind Tree Hotel' => [
                ['Standard Room', 2, 1, 20, false, false, 160],
                ['Family Room', 2, 2, 5, true, false, 245],
            ],
            'Mara Sentim Camp' => [
                ['Standard Tent', 2, 1, 22, false, false, 214.22],
                ['Two Bedroom Family Tent', 4, 3, 4, true, false, 398],
            ],
            'Mara Simba Lodge' => [
                ['Standard Room', 2, 1, 28, false, false, 264.63],
                ['Family Room', 2, 3, 6, true, false, 375],
            ],
            'Serengeti Serena Safari Lodge' => [
                ['Standard Room', 2, 1, 30, false, false, 320],
                ['Executive Suite', 2, 2, 4, true, false, 510],
            ],
            'Zanzibar Serena Hotel' => [
                ['Double Ocean Room', 2, 1, 18, false, false, 280],
                ['Family Room', 2, 3, 5, true, false, 390],
                ['Presidential Suite', 2, 2, 1, true, false, 850],
            ],
        ];

        $roomIds = [];
        foreach ($roomDefinitions as $hotelName => $rooms) {
            foreach ($rooms as [$name, $adults, $children, $inventory, $family, $interconnecting, $buyRate]) {
                $roomId = DB::table('room_types')->insertGetId([
                    'hotel_id' => $hotelIds[$hotelName], 'name' => $name,
                    'max_adults' => $adults, 'max_children' => $children, 'inventory' => $inventory,
                    'is_family_room' => $family, 'is_interconnecting' => $interconnecting,
                    'active' => true, 'notes' => $interconnecting ? 'Must be confirmed as a paired room set.' : null,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                $roomIds[$hotelName.'|'.$name] = $roomId;
                $markup = (float) DB::table('hotels')->where('id', $hotelIds[$hotelName])->value('default_markup_percent');

                foreach ([
                    ['Low Season', '2026-01-01', '2026-05-31', $buyRate],
                    ['High Season', '2026-06-01', '2026-10-31', $buyRate * 1.22],
                    ['Festive Season', '2026-11-01', '2027-01-05', $buyRate * 1.38],
                ] as [$season, $from, $to, $seasonBuy]) {
                    DB::table('hotel_rates')->insert([
                        'room_type_id' => $roomId, 'season_name' => $season,
                        'valid_from' => $from, 'valid_to' => $to, 'meal_plan' => 'Full Board',
                        'occupancy_basis' => 'per_room', 'buy_rate' => round($seasonBuy, 2),
                        'markup_percent' => $markup, 'sell_rate' => round($seasonBuy * (1 + $markup / 100), 2),
                        'currency' => 'USD', 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        }

        $activities = [
            ['Nature Walk at Maasai Heritage Eco Home', 'Hiking', 'Kenya', 'Nairobi', 'Maasai Heritage', 'per_person', 25, 28, 20, 3],
            ['Mount Longonot Guided Hike', 'Hiking', 'Kenya', 'Naivasha', 'Rift Valley Guides', 'per_person', 75, 30, 18, 7],
            ['Maasai Mara Balloon Safari', 'Balloon Safari', 'Kenya', 'Maasai Mara', 'Governors Balloon', 'per_person', 420, 18, 24, 4],
            ['Nairobi Airport Meet and Assist', 'Transfer', 'Kenya', 'Nairobi', 'SafariFlow Operations', 'per_vehicle', 45, 25, 7, 2],
            ['Gorilla Trekking Permit', 'Permit', 'Uganda', 'Bwindi', 'Uganda Wildlife Authority', 'per_person', 800, 10, 12, 8],
        ];
        $activityIds = [];
        foreach ($activities as [$name, $category, $country, $location, $supplier, $calculation, $buy, $markup, $capacity, $hours]) {
            $activityIds[$name] = DB::table('tour_activities')->insertGetId([
                'name' => $name, 'category' => $category, 'country' => $country, 'location' => $location,
                'supplier' => $supplier, 'calculation_type' => $calculation, 'buy_rate' => $buy,
                'markup_percent' => $markup, 'sell_rate' => round($buy * (1 + $markup / 100), 2),
                'currency' => 'USD', 'daily_capacity' => $capacity, 'duration_hours' => $hours,
                'status' => 'active', 'notes' => $category === 'Hiking' ? 'Includes guide, safety briefing and hiking logistics.' : null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $vehicleIds = [];
        foreach ([
            ['KDG 418N', 'Savanna One', 7, 'Nairobi', 224, 'James Mwangi'],
            ['KDM 203R', 'Savanna Two', 7, 'Nairobi', 224, 'Peter Otieno'],
            ['KDL 907T', 'Mara Explorer', 6, 'Maasai Mara', 245, 'Grace Wambui'],
        ] as [$plate, $name, $capacity, $location, $rate, $driver]) {
            $vehicleIds[$plate] = DB::table('vehicles')->insertGetId([
                'number_plate' => $plate, 'name' => $name, 'type' => 'Safari Land Cruiser',
                'capacity' => $capacity, 'current_location' => $location, 'daily_buy_rate' => $rate,
                'markup_percent' => 20, 'currency' => 'USD', 'driver_name' => $driver,
                'status' => 'available', 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        foreach ([
            ['Nairobi', 'Naivasha', 95, 2.25, true, null],
            ['Nairobi', 'Maasai Mara', 270, 5.50, true, null],
            ['Naivasha', 'Maasai Mara', 245, 5.25, true, null],
            ['Maasai Mara', 'Serengeti', 385, 8.50, true, 'Allow border formalities and use the Isebania crossing.'],
            ['Nairobi', 'Serengeti', 710, 13.50, false, 'Split this movement with an overnight stop or use a scheduled flight.'],
            ['Nairobi', 'Bwindi', 1180, 21, false, 'This route cannot be driven in one safari day. Add an overnight stop or flight.'],
            ['Nairobi', 'Nanyuki', 200, 3.50, true, null],
        ] as [$from, $to, $km, $hours, $allowed, $warning]) {
            DB::table('route_distances')->insert([
                'from_location' => $from, 'to_location' => $to, 'distance_km' => $km,
                'minimum_hours' => $hours, 'same_day_allowed' => $allowed, 'warning' => $warning,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $flightClient = DB::table('clients')->where('email', 'sophie@example.com')->value('id');
        foreach ([
            ['FLT-2606-001', 'Sophie Martin', 'adult', 'Kenya Airways', 'KQ 482', 'domestic', 'economy', 'NBO', 'MBA', '2026-07-06 09:10:00', '2026-07-06 10:20:00', 'SH7KQX', '7061234567890', 115, 42, 12, 'ticketed', 'paid'],
            ['FLT-2606-002', 'Lukas Schneider', 'adult', 'Precision Air', 'PW 712', 'international', 'economy', 'JRO', 'ZNZ', '2026-08-04 13:30:00', '2026-08-04 14:50:00', 'PW8LTS', null, 180, 55, 10, 'confirmed', 'part_paid'],
            ['FLT-2606-003', 'Olivia Bennett', 'adult', 'Safarilink', 'F2 51', 'charter', 'economy', 'WIL', 'MRE', '2026-07-12 07:30:00', '2026-07-12 08:25:00', null, null, 265, 0, 15, 'requested', 'unpaid'],
        ] as [$reference, $passenger, $passengerType, $airline, $flightNumber, $flightType, $cabin, $origin, $destination, $departure, $arrival, $pnr, $ticket, $fare, $taxes, $markup, $bookingStatus, $paymentStatus]) {
            DB::table('flight_bookings')->insert([
                'request_reference' => $reference, 'client_id' => $flightClient,
                'passenger_name' => $passenger, 'passenger_type' => $passengerType,
                'passport_number' => 'P'.random_int(1000000, 9999999),
                'airline' => $airline, 'flight_number' => $flightNumber,
                'flight_type' => $flightType, 'cabin_class' => $cabin,
                'origin_code' => $origin, 'destination_code' => $destination,
                'departure_at' => $departure, 'arrival_at' => $arrival,
                'pnr' => $pnr, 'ticket_number' => $ticket,
                'baggage_allowance' => '23kg checked + 7kg cabin',
                'supplier' => $airline, 'base_fare' => $fare, 'taxes' => $taxes,
                'markup_percent' => $markup,
                'selling_total' => round(($fare + $taxes) * (1 + $markup / 100), 2),
                'currency' => 'USD', 'payment_deadline' => '2026-06-25',
                'payment_status' => $paymentStatus, 'booking_status' => $bookingStatus,
                'notes' => 'Exact airline booking record.', 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $clientId = DB::table('clients')->where('email', 'sophie@example.com')->value('id') ?? DB::table('clients')->value('id');
        $quotationId = DB::table('quotations')->insertGetId([
            'client_id' => $clientId, 'reference' => 'QT-2026-0108',
            'title' => 'Kenya Family Safari - Mara & Naivasha', 'start_date' => '2026-07-07',
            'duration_days' => 10, 'guest_count' => 4, 'start_location' => 'Nairobi',
            'currency' => 'USD', 'office_markup_percent' => 20, 'misc_markup_percent' => 5,
            'exchange_rate' => 1, 'status' => 'active', 'frozen' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $days = [];
        for ($day = 1; $day <= 10; $day++) {
            $date = now()->setDate(2026, 7, 7)->addDays($day - 1)->toDateString();
            $from = $day === 1 ? 'Nairobi' : ($day < 4 ? 'Naivasha' : 'Maasai Mara');
            $to = $day === 1 ? 'Naivasha' : ($day === 4 ? 'Maasai Mara' : $from);
            $days[$day] = DB::table('quotation_days')->insertGetId([
                'quotation_id' => $quotationId, 'day_number' => $day, 'travel_date' => $date,
                'from_location' => $from, 'to_location' => $to,
                'description' => $day === 1 ? 'Airport welcome, briefing and transfer to Naivasha.' : 'Private safari program.',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $this->addQuoteItem($days[1], 'activity', $activityIds['Nairobi Airport Meet and Assist'], 'Airport meet and assist', 'SafariFlow Operations', 'per_vehicle', 1, 45, 25);
        $this->addQuoteItem($days[1], 'vehicle', $vehicleIds['KDG 418N'], 'Private safari vehicle - KDG 418N', 'SafariFlow Fleet', 'per_vehicle', 1, 224, 20);
        $this->addQuoteItem($days[1], 'room', $roomIds['Tamarind Tree Hotel|Family Room'], 'Tamarind Tree Hotel: Family Room (Full Board)', 'Tamarind Tree Hotel', 'per_room', 1, 245, 20);
        $this->addQuoteItem($days[2], 'activity', $activityIds['Mount Longonot Guided Hike'], 'Mount Longonot Guided Hike', 'Rift Valley Guides', 'per_person', 4, 75, 30);
        $this->addQuoteItem($days[4], 'room', $roomIds['Mara Simba Lodge|Family Room'], 'Mara Simba Lodge: Family Room (Full Board)', 'Mara Simba Lodge', 'per_room', 1, 375, 25);
        $this->addQuoteItem($days[5], 'activity', $activityIds['Maasai Mara Balloon Safari'], 'Maasai Mara Balloon Safari', 'Governors Balloon', 'per_person', 4, 420, 18);

        app(QuotationPricingService::class)->recalculate($quotationId);

        DB::table('quotation_payments')->insert([
            'quotation_id' => $quotationId, 'reference' => 'PAY-8891', 'amount' => 2500,
            'currency' => 'USD', 'paid_at' => '2026-06-10', 'method' => 'Bank transfer',
            'notes' => 'Initial safari deposit.', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $quoteItems = DB::table('quotation_items')
            ->join('quotation_days', 'quotation_days.id', '=', 'quotation_items.quotation_day_id')
            ->where('quotation_days.quotation_id', $quotationId)
            ->select('quotation_items.*', 'quotation_days.travel_date')
            ->get();
        foreach ($quoteItems->take(4) as $index => $item) {
            $type = $item->item_type === 'vehicle' ? 'vehicle' : ($item->item_type === 'room' ? 'room' : 'activity');
            DB::table('reservations')->insert([
                'quotation_id' => $quotationId, 'quotation_item_id' => $item->id,
                'reservation_type' => $type, 'resource_id' => $item->source_id,
                'starts_at' => $item->travel_date.' 08:00:00',
                'ends_at' => \Carbon\Carbon::parse($item->travel_date)->addDay()->format('Y-m-d').' 08:00:00',
                'quantity' => max(1, (int) $item->quantity), 'supplier' => $item->source,
                'confirmation_number' => $index < 2 ? 'CNF-'.(8100 + $index) : null,
                'assigned_person' => $type === 'vehicle' ? 'James Mwangi' : null,
                'number_plate' => $type === 'vehicle' ? 'KDG 418N' : null,
                'amount_due' => $item->buy_total, 'actual_cost' => $item->buy_total,
                'paid_amount' => $index < 2 ? $item->buy_total : 0,
                'payment_deadline' => '2026-06-25',
                'status' => $index < 2 ? 'confirmed' : 'requested',
                'notes' => 'Seeded reservation linked to quotation.',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        DB::table('trip_expenses')->insert([
            'quotation_id' => $quotationId, 'category' => 'Game ranger',
            'supplier' => 'Maasai Mara Conservancy', 'description' => 'Additional private ranger requested during the game drive.',
            'amount' => 85, 'currency' => 'USD', 'was_quoted' => false,
            'charged_to_client' => false, 'expense_date' => '2026-07-11',
            'payment_reference' => 'MPESA-RANGER-01', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->seedModuleRegisters();
    }

    private function addQuoteItem(
        int $dayId,
        string $type,
        int $sourceId,
        string $title,
        string $source,
        string $calculation,
        float $quantity,
        float $buy,
        float $markup
    ): void {
        $sell = round($buy * (1 + $markup / 100), 2);
        DB::table('quotation_items')->insert([
            'quotation_day_id' => $dayId, 'item_type' => $type, 'source_id' => $sourceId,
            'title' => $title, 'source' => $source, 'calculation_type' => $calculation,
            'quantity' => $quantity, 'buy_unit_price' => $buy, 'markup_percent' => $markup,
            'sell_unit_price' => $sell, 'buy_total' => $buy * $quantity, 'sell_total' => $sell * $quantity,
            'currency' => 'USD', 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function seedModuleRegisters(): void
    {
        $records = [
            'facility-list' => [
                ['Swimming Pool', 'FAC-001', 'active', null, null, 'Outdoor infinity pool with child-safe shallow end.'],
                ['Free Wi-Fi', 'FAC-002', 'active', null, null, 'High-speed guest internet in rooms and public areas.'],
                ['Wheelchair Access', 'FAC-003', 'active', null, null, 'Accessible rooms, ramps and public facilities.'],
                ['Family Friendly', 'FAC-004', 'active', null, null, 'Family rooms, children menus and flexible meal times.'],
                ['Air Conditioning', 'FAC-005', 'active', null, null, 'Available in all guest rooms.'],
            ],
            'facility-categories' => [
                ['Room Amenities', 'FCAT-01', 'active', null, null, 'In-room comfort and guest services.'],
                ['Leisure & Wellness', 'FCAT-02', 'active', null, null, 'Pools, spa, gym and recreation.'],
                ['Accessibility', 'FCAT-03', 'active', null, null, 'Facilities for guests with limited mobility.'],
            ],
            'facility-icons' => [
                ['Pool icon', 'waves', 'active', null, null, 'Lucide icon: waves.'],
                ['Wi-Fi icon', 'wifi', 'active', null, null, 'Lucide icon: wifi.'],
                ['Accessibility icon', 'accessibility', 'active', null, null, 'Lucide icon: accessibility.'],
            ],
            'accommodation-facilities' => [
                ['Four Points: Airport shuttle & pool', 'AF-001', 'confirmed', null, null, 'Assigned to Four Points by Sheraton Nairobi Airport.'],
                ['Mara Simba: Family friendly & pool', 'AF-002', 'confirmed', null, null, 'Assigned to Mara Simba Lodge.'],
            ],
            'activity-facilities' => [
                ['Longonot hike: First aid & walking poles', 'ACTF-01', 'confirmed', null, null, 'Safety facilities supplied by Rift Valley Guides.'],
                ['Balloon safari: Bush breakfast', 'ACTF-02', 'confirmed', null, null, 'Breakfast and transfer included.'],
            ],
            'park-fees' => [
                ['Maasai Mara non-resident adult', 'PARK-KE-MMR-A', 'active', '2026-07-01', 200, 'Per adult per 24 hours.'],
                ['Maasai Mara non-resident child', 'PARK-KE-MMR-C', 'active', '2026-07-01', 50, 'Child rate, proof of age required.'],
                ['Serengeti adult conservation fee', 'PARK-TZ-SER-A', 'active', '2026-07-01', 82.60, 'VAT included.'],
            ],
            'conservation-fees' => [
                ['Olare Motorogi Conservancy fee', 'CONS-OM-01', 'active', '2026-01-01', 140, 'Per adult per night.'],
                ['Ngorongoro crater service fee', 'CONS-NCA-01', 'active', '2026-01-01', 295, 'Per vehicle crater service charge.'],
            ],
            'activity-fees' => [
                ['Gorilla permit adult', 'AFEE-UG-01', 'active', '2026-01-01', 800, 'Per trek; subject to permit availability.'],
                ['Balloon safari fee', 'AFEE-KE-02', 'active', '2026-01-01', 420, 'Per adult including breakfast.'],
            ],
            'vehicle-fees' => [
                ['Safari Land Cruiser daily hire', 'VEH-FEE-01', 'active', '2026-01-01', 224, 'Supplier buy-in before markup.'],
                ['Airport transfer van', 'VEH-FEE-02', 'active', '2026-01-01', 65, 'Jomo Kenyatta Airport to Nairobi hotel.'],
            ],
            'airport-transfer-fees' => [
                ['NBO airport meet and assist', 'APT-001', 'active', '2026-01-01', 45, 'Per arriving party.'],
                ['NBO to Karen transfer', 'APT-002', 'active', '2026-01-01', 75, 'Per vehicle, daytime operation.'],
            ],
            'domestic-flight-fares' => [
                ['Wilson to Maasai Mara one way', 'AIR-WIL-MRE', 'active', '2026-01-01', 265, 'Indicative adult fare excluding excess baggage.'],
                ['Nairobi to Mombasa one way', 'AIR-NBO-MBA', 'active', '2026-01-01', 157, 'Base fare and tax before markup.'],
            ],
            'visa-fees' => [
                ['Kenya electronic travel authorisation', 'VISA-KE', 'active', '2026-01-01', 32.50, 'Client applies online before travel.'],
                ['Tanzania single-entry visa', 'VISA-TZ', 'active', '2026-01-01', 50, 'Most nationalities; verify passport.'],
            ],
            'taxes' => [
                ['Kenya VAT', 'TAX-KE-VAT', 'active', '2026-01-01', 16, 'Percentage; verify whether supplier rates include VAT.'],
                ['Tanzania tourism development levy', 'TAX-TZ-TDL', 'active', '2026-01-01', 1.5, 'Percentage where applicable.'],
            ],
            'supplements' => [
                ['Christmas supplement', 'SUP-XMAS', 'active', '2026-12-20', 85, 'Per person per night during festive period.'],
                ['Single room supplement', 'SUP-SGL', 'active', '2026-01-01', 95, 'Indicative daily supplement.'],
            ],
            'surcharges' => [
                ['Fuel surcharge', 'SUR-FUEL', 'active', '2026-01-01', 35, 'Applied per safari vehicle day when announced.'],
                ['Late airport arrival surcharge', 'SUR-LATE', 'active', '2026-01-01', 25, 'After 22:00 local time.'],
            ],
            'discounts' => [
                ['Early booking discount', 'DISC-EARLY', 'active', '2026-01-01', 5, 'Percentage for confirmation 180 days ahead.'],
                ['Returning guest discount', 'DISC-RETURN', 'active', '2026-01-01', 3, 'Subject to director approval.'],
            ],
            'margin-rules' => [
                ['Premium hotel margin', 'MRG-HOT-PREM', 'active', '2026-01-01', 28, 'Default percentage for premium and luxury hotels.'],
                ['Activity margin', 'MRG-ACT', 'active', '2026-01-01', 25, 'Standard activity markup unless contracted otherwise.'],
                ['Flight service margin', 'MRG-FLT', 'active', '2026-01-01', 10, 'Applied to airfare plus taxes.'],
            ],
            'exchange-rates' => [
                ['USD to KES', 'FX-USD-KES', 'active', now()->toDateString(), 129.50, 'Operational planning rate; update daily.'],
                ['USD to GBP', 'FX-USD-GBP', 'active', now()->toDateString(), 0.7717, 'Frozen per quotation snapshot.'],
                ['USD to EUR', 'FX-USD-EUR', 'active', now()->toDateString(), 0.92, 'Operational planning rate.'],
            ],
            'currency-settings' => [
                ['US Dollar', 'USD', 'active', null, null, 'Primary quotation currency.'],
                ['Kenyan Shilling', 'KES', 'active', null, null, 'Primary local payment currency.'],
                ['Euro', 'EUR', 'active', null, null, 'International client currency.'],
            ],
            'flight-suppliers' => [
                ['Kenya Airways', 'AIR-SUP-01', 'active', null, null, 'IATA airline; domestic and international.'],
                ['Safarilink Aviation', 'AIR-SUP-02', 'active', null, null, 'Domestic safari air service.'],
                ['AirKenya Express', 'AIR-SUP-03', 'active', null, null, 'Domestic safari air service.'],
            ],
            'countries' => [
                ['Kenya', 'KE', 'active', null, null, 'Primary operating country.'],
                ['Tanzania', 'TZ', 'active', null, null, 'Safari and beach destination.'],
                ['Uganda', 'UG', 'active', null, null, 'Primate and adventure destination.'],
                ['Rwanda', 'RW', 'active', null, null, 'Gorilla and luxury short-break destination.'],
            ],
            'regions' => [
                ['Maasai Mara ecosystem', 'REG-KE-MMR', 'active', null, null, 'Southwestern Kenya safari region.'],
                ['Northern Tanzania circuit', 'REG-TZ-NORTH', 'active', null, null, 'Arusha, Tarangire, Ngorongoro and Serengeti.'],
            ],
            'parks-reserves' => [
                ['Maasai Mara National Reserve', 'PARK-MMR', 'active', null, null, 'Big cats and migration river crossings.'],
                ['Serengeti National Park', 'PARK-SER', 'active', null, null, 'World heritage migration ecosystem.'],
                ['Bwindi Impenetrable National Park', 'PARK-BWI', 'active', null, null, 'Mountain gorilla trekking.'],
            ],
            'cities-towns' => [
                ['Nairobi', 'CITY-NBO', 'active', null, null, 'Main international arrival hub.'],
                ['Arusha', 'CITY-ARK', 'active', null, null, 'Northern Tanzania safari gateway.'],
                ['Entebbe', 'CITY-EBB', 'active', null, null, 'Uganda international arrival hub.'],
            ],
            'start-locations' => [
                ['Jomo Kenyatta International Airport', 'START-NBO', 'active', null, null, 'Airport code NBO.'],
                ['Wilson Airport', 'START-WIL', 'active', null, null, 'Domestic safari flights.'],
                ['Arusha hotel', 'START-ARU', 'active', null, null, 'Northern Tanzania road safari start.'],
            ],
            'route-distances' => [
                ['Nairobi to Maasai Mara', 'ROUTE-NBO-MMR', 'active', null, 270, '5.5 hours minimum excluding stops.'],
                ['Nairobi to Naivasha', 'ROUTE-NBO-NAV', 'active', null, 95, '2.25 hours minimum.'],
                ['Nairobi to Serengeti', 'ROUTE-NBO-SER', 'warning', null, 710, 'Not realistic in one day; overnight or fly.'],
            ],
            'reservations' => [
                ['Mara Simba family room request', 'RES-DEMO-01', 'confirmed', '2026-07-10', 375, 'Linked demo supplier confirmation.'],
            ],
            'supplier-confirmations' => [
                ['Mara Simba Lodge confirmation', 'CNF-8100', 'confirmed', '2026-06-20', 375, 'Family room held for QT-2026-0108.'],
                ['Governors Balloon request', 'CNF-PENDING', 'pending', '2026-06-22', 1680, 'Awaiting supplier reply.'],
            ],
            'daily-movements' => [
                ['Airport to Naivasha movement', 'MOV-2026-0707', 'confirmed', '2026-07-07', null, 'KDG 418N · Driver James Mwangi · 4 guests.'],
                ['Naivasha to Maasai Mara', 'MOV-2026-0710', 'planned', '2026-07-10', null, '245 km; depart by 08:00.'],
            ],
            'driver-briefings' => [
                ['James Mwangi briefing - QT-2026-0108', 'BRF-0706', 'confirmed', '2026-07-06', null, 'Airport signage, guest contacts, dietary details and route plan.'],
            ],
            'rooming-lists' => [
                ['QT-2026-0108 rooming list', 'ROOM-0108', 'confirmed', '2026-07-01', null, 'One family room, two adults and two children.'],
            ],
            'guest-manifest' => [
                ['QT-2026-0108 guest manifest', 'MAN-0108', 'confirmed', '2026-07-01', null, 'Passport and emergency details verified.'],
            ],
            'pre-departure-checklist' => [
                ['QT-2026-0108 departure checklist', 'PDC-0108', 'pending', '2026-07-05', null, 'Flights, rooms, permits, payments and driver brief.'],
            ],
            'emergency-contacts' => [
                ['Flying Doctors emergency line', 'EMG-FD', 'active', null, null, '+254 20 699 2000.'],
                ['Shishi operations duty phone', 'EMG-OPS', 'active', null, null, '+254 725 346 022.'],
            ],
            'operations-calendar' => [
                ['Kenya Family Safari departure', 'CAL-DEP-01', 'confirmed', '2026-07-07', null, '10-day departure from Nairobi.'],
                ['Vehicle service KDM 203R', 'CAL-MNT-02', 'planned', '2026-07-03', 180, 'Preventive maintenance before safari.'],
            ],
            'invoices' => [
                ['Invoice QT-2026-0108 deposit', 'INV-260610', 'part_paid', '2026-06-10', 5000, 'USD client invoice.'],
            ],
            'receipts' => [
                ['Receipt PAY-8891', 'RCT-8891', 'confirmed', '2026-06-10', 2500, 'Bank transfer received.'],
            ],
            'client-payments' => [
                ['Sophie Martin safari deposit', 'PAY-8891', 'confirmed', '2026-06-10', 2500, 'Applied to QT-2026-0108.'],
            ],
            'supplier-payments' => [
                ['Mara Simba Lodge deposit', 'SUPPAY-001', 'confirmed', '2026-06-20', 375, 'Supplier payment for family room.'],
                ['Game ranger extra payment', 'SUPPAY-002', 'confirmed', '2026-07-11', 85, 'Not charged to client; included in actual cost.'],
            ],
            'payment-deadlines' => [
                ['Mara Simba balance', 'DUE-001', 'pending', '2026-06-25', 375, 'Pay before release date.'],
                ['Balloon safari full payment', 'DUE-002', 'pending', '2026-06-28', 1680, 'Permit-like non-refundable service.'],
            ],
            'outstanding-balances' => [
                ['QT-2026-0108 client balance', 'BAL-0108', 'pending', '2026-06-30', 2500, 'Quoted balance before final reconciliation.'],
            ],
            'consultant-commissions' => [
                ['Daniel Kimani July commission', 'COM-2026-07', 'pending', '2026-07-31', 240, 'Calculated after client completes safari.'],
            ],
            'website-pages' => [
                ['Shishi Footsteps home page', 'WEB-HOME', 'published', null, null, 'Golf, safari, beach and culture positioning.'],
                ['Contact and enquiry page', 'WEB-CONTACT', 'published', null, null, 'Feeds requests into sales pipeline.'],
            ],
            'safari-packages' => [
                ['Kenya Golf & Safari Signature', 'PKG-KE-GOLF', 'published', null, 5850, 'Nairobi golf, Maasai Mara and Diani.'],
                ['Tanzania Fairways & Migration', 'PKG-TZ-GOLF', 'draft', null, 6950, 'Arusha golf and Serengeti migration.'],
            ],
            'featured-packages' => [
                ['Kenya Wild & Coastal', 'FEAT-001', 'published', null, 4290, 'Homepage feature.'],
            ],
            'blog-travel-guides' => [
                ['Kenya Through Your Senses', 'BLOG-001', 'published', '2026-05-20', null, 'Sensory travel guide and itinerary inspiration.'],
                ['Golf and Safari Packing Guide', 'BLOG-002', 'draft', '2026-07-01', null, 'Equipment, dress codes and safari essentials.'],
            ],
            'testimonials' => [
                ['Jonathan Davis testimonial', 'TEST-001', 'published', null, null, 'They treated me like family.'],
            ],
            'gallery' => [
                ['Maasai Mara sunrise collection', 'GAL-001', 'published', null, null, '12 optimized web images.'],
                ['Kenya golf courses collection', 'GAL-002', 'published', null, null, '18 optimized web images.'],
            ],
            'seo-settings' => [
                ['Homepage SEO metadata', 'SEO-HOME', 'active', null, null, 'Luxury golf tours and safaris in Africa.'],
            ],
            'enquiry-forms' => [
                ['Main tailor-made journey form', 'FORM-MAIN', 'active', null, null, 'Creates website request and sales follow-up task.'],
            ],
            'email-campaigns' => [
                ['Great Migration early booking', 'CAM-2026-MIG', 'draft', '2026-07-01', null, 'Segment: previous safari and golf guests.'],
            ],
            'whatsapp-templates' => [
                ['New enquiry acknowledgement', 'WA-ACK', 'active', null, null, 'Thank client and confirm consultant assignment.'],
                ['Payment reminder', 'WA-PAY', 'active', null, null, 'Send three days before supplier deadline.'],
            ],
            'social-media-leads' => [
                ['Instagram Kenya golf lead campaign', 'SOC-IG-KE', 'active', '2026-06-01', 1200, 'Feeds tagged leads into website requests.'],
            ],
            'home-page' => [
                ['Hero: Where fairways meet the wild', 'CMS-HERO', 'published', null, null, 'Shishi Footsteps homepage hero section.'],
            ],
            'about-page' => [
                ['About Shishi Footsteps', 'CMS-ABOUT', 'published', null, null, 'Mission, vision and local expertise.'],
            ],
            'contact-page' => [
                ['Contact & journey planning', 'CMS-CONTACT', 'published', null, null, 'Phone, email and enquiry funnel.'],
            ],
            'menus' => [
                ['Primary mega menu', 'CMS-MENU-01', 'published', null, null, 'Tee Off, Beyond Golf, Journeys and About.'],
                ['Footer destination menu', 'CMS-MENU-02', 'published', null, null, 'Country and experience links.'],
            ],
            'banners' => [
                ['Homepage golf and safari hero', 'BAN-HOME-01', 'published', null, null, 'Desktop and mobile imagery configured.'],
            ],
            'company-profile' => [
                ['Shishi Footsteps Ltd', 'COMPANY', 'active', null, null, 'Nairobi, Kenya · info@shishifootsteps.com · +254725346022.'],
            ],
            'users' => [
                ['Amara Njeri', 'USR-ADMIN', 'active', null, null, 'Administrator.'],
                ['Daniel Kimani', 'USR-SALES', 'active', null, null, 'Sales consultant.'],
                ['Linet Achieng', 'USR-RES', 'active', null, null, 'Reservations consultant.'],
                ['Joseph Maina', 'USR-OPS', 'active', null, null, 'Operations coordinator.'],
                ['Faith Wanjiku', 'USR-FIN', 'active', null, null, 'Finance officer.'],
            ],
            'roles-permissions' => [
                ['Sales consultant', 'ROLE-SALES', 'active', null, null, 'Leads, clients, quotations and follow-up.'],
                ['Reservations officer', 'ROLE-RES', 'active', null, null, 'Supplier availability, bookings and deadlines.'],
                ['Finance officer', 'ROLE-FIN', 'active', null, null, 'Client payments, supplier payments and profitability.'],
            ],
            'languages' => [
                ['English', 'LANG-EN', 'active', null, null, 'Default and fallback language.'],
                ['French', 'LANG-FR', 'active', null, null, 'Website and admin enabled.'],
                ['German', 'LANG-DE', 'active', null, null, 'Website and admin enabled.'],
            ],
            'currencies' => [
                ['US Dollar', 'USD', 'active', null, null, 'Primary system currency.'],
                ['Kenyan Shilling', 'KES', 'active', null, null, 'Local supplier payments.'],
                ['British Pound', 'GBP', 'active', null, null, 'International quotation currency.'],
            ],
            'email-templates' => [
                ['New enquiry acknowledgement', 'MAIL-ACK', 'active', null, null, 'Sent immediately after website request.'],
                ['Quotation ready', 'MAIL-QUOTE', 'active', null, null, 'Proposal link and payment instructions.'],
                ['Pre-departure briefing', 'MAIL-PREDEP', 'active', null, null, 'Travel documents and emergency contacts.'],
            ],
            'pdf-templates' => [
                ['Client quotation PDF', 'PDF-QUOTE', 'active', null, null, 'Day-by-day itinerary with selling prices.'],
                ['Driver briefing PDF', 'PDF-DRIVER', 'active', null, null, 'Movements, contacts and guest needs.'],
            ],
            'notification-settings' => [
                ['Supplier payment deadline reminder', 'NOTIF-PAY', 'active', null, null, 'Notify reservations and finance 5 days before due.'],
                ['Unassigned enquiry alert', 'NOTIF-LEAD', 'active', null, null, 'Notify sales manager after 30 minutes.'],
            ],
            'audit-logs' => [
                ['Quotation QT-2026-0108 updated', 'AUD-001', 'completed', now()->toDateString(), null, 'Amara Njeri changed hotel selection.'],
                ['Supplier payment recorded', 'AUD-002', 'completed', now()->toDateString(), 375, 'Faith Wanjiku recorded payment.'],
            ],
            'backup-settings' => [
                ['Nightly database backup', 'BACKUP-DB', 'active', null, null, 'Daily at 02:00 Africa/Nairobi.'],
                ['Weekly media backup', 'BACKUP-MEDIA', 'active', null, null, 'Sunday at 03:00.'],
            ],
            'terms-conditions' => [
                ['Safari booking terms 2026', 'TERMS-2026', 'active', '2026-01-01', null, 'Deposits, cancellation, force majeure and liability.'],
            ],
        ];

        $allSlugs = collect(config('navigation'))->flatMap(fn ($menu) => collect($menu['children'] ?? [])->map(fn ($child) => \Illuminate\Support\Str::slug($child)))->unique();
        foreach ($allSlugs as $slug) {
            if (! isset($records[$slug])) {
                $records[$slug] = [
                    [\Illuminate\Support\Str::headline($slug).' demonstration record', strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', strtoupper($slug)), 0, 6)).'-001', 'active', now()->toDateString(), null, 'Ready for department review, editing and additional records.'],
                ];
            }
        }

        foreach ($records as $slug => $moduleRecords) {
            foreach ($moduleRecords as [$title, $reference, $status, $effectiveDate, $amount, $notes]) {
                DB::table('module_records')->insert([
                    'module_slug' => $slug, 'title' => $title, 'reference' => $reference,
                    'status' => $status, 'effective_date' => $effectiveDate,
                    'amount' => $amount, 'notes' => $notes,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }
}
