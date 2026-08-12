<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AccommodationV2Controller;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityV2Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\ClientProposalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ItineraryBuilderController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadV2Controller;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MailSettingsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentLinkController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ProposalPlanningController;
use App\Http\Controllers\ProposalWorkspaceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\ItineraryTemplateController;
use App\Http\Controllers\PublicProposalController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ModuleRecordController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\IncomingMailController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ====================
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/destinations', [PublicController::class, 'destinations'])->name('public.destinations');
Route::get('/destinations/{slug}', [PublicController::class, 'destinationShow'])->name('public.destinations.show');
Route::get('/destinations/{slug}/{section}', [PublicController::class, 'destinationSection'])->name('public.destinations.section');
Route::get('/safaris', [PublicController::class, 'safaris'])->name('public.safaris');
Route::get('/safaris/{slug}', [PublicController::class, 'safariShow'])->name('public.safaris.show');
Route::get('/accommodation', [PublicController::class, 'accommodations'])->name('public.accommodations');
Route::get('/accommodation/{slug}', [PublicController::class, 'accommodationShow'])->name('public.accommodations.show');
Route::get('/experiences', [PublicController::class, 'experiences'])->name('public.experiences');
Route::get('/experiences/{slug}', [PublicController::class, 'experienceShow'])->name('public.experiences.show');
Route::get('/itineraries', [PublicController::class, 'itineraries'])->name('public.itineraries');
Route::get('/itineraries/{slug}', [PublicController::class, 'itineraryShow'])->name('public.itineraries.show');
Route::get('/golf', [PublicController::class, 'golf'])->name('public.golf');
Route::get('/tee-off', [PublicController::class, 'golf'])->name('public.tee-off');
Route::get('/tee-off/{country}', [PublicController::class, 'teeOffCountry'])->name('public.tee-off.country');
Route::get('/faqs', [PublicController::class, 'faqs'])->name('public.faqs');
Route::get('/frequently-asked-questions', [PublicController::class, 'faqs'])->name('public.faqs.legacy');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::get('/blog', [PublicController::class, 'blog'])->name('public.blog');
Route::get('/blog/{slug}', [PublicController::class, 'blogPost'])->name('public.blog.post');
Route::get('/booking', [PublicController::class, 'booking'])->name('public.booking');
Route::get('/booking-form/{token?}', [PublicController::class, 'bookingForm'])->name('public.booking.form');
Route::post('/booking-form/{token?}', [PublicController::class, 'submitBookingForm'])->name('public.booking.form.submit');
Route::post('/enquire', [PublicController::class, 'enquire'])->name('enquire');
Route::get('/search/itineraries', [SearchController::class, 'itineraries'])->name('public.search.itineraries');
Route::get('/search/countries', [SearchController::class, 'countries'])->name('public.search.countries');
Route::post('/newsletter', [PublicController::class, 'subscribe'])->name('public.newsletter');
Route::post('/chat/start', [ChatController::class, 'start'])->middleware('throttle:10,1')->name('chat.start');
Route::get('/chat/{token}', [ChatController::class, 'messages'])->middleware('throttle:60,1')->name('chat.messages');
Route::post('/chat/{token}', [ChatController::class, 'visitorReply'])->middleware('throttle:30,1')->name('chat.reply');
Route::get('/proposal/{token}', [ClientProposalController::class, 'show'])->name('proposal.client');
Route::get('/proposal/{token}/documents/{document}', [ClientProposalController::class, 'document'])->name('proposal.client.document');
Route::get('/language/{locale}', [LocaleController::class, 'update'])->name('locale.update');
Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/two-factor/challenge', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
Route::post('/two-factor/challenge', [TwoFactorController::class, 'verifyChallenge'])->name('two-factor.challenge.verify');

// ==================== PUBLIC PROPOSAL ====================
Route::get('/proposal-view/{token}', [PublicProposalController::class, 'show'])->name('public.proposal.show');
Route::post('/proposal-view/{token}/accept', [PublicProposalController::class, 'accept'])->name('public.proposal.accept');
Route::post('/proposal-view/{token}/request-changes', [PublicProposalController::class, 'requestChanges'])->name('public.proposal.request-changes');
Route::get('/proposal-view/{token}/download', [PublicProposalController::class, 'download'])->name('public.proposal.download');

// ==================== ADMIN ====================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // REQUESTS
    Route::prefix('requests')->name('requests.')->group(function () {
        // Static routes FIRST (before wildcard {request})
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/accommodation-bookings', [ReservationController::class, 'accommodationBookings'])->name('accommodation-bookings');
        Route::get('/accommodation-bookings/export', [ReservationController::class, 'exportAccommodationBookings'])->name('accommodation-bookings.export');
        Route::get('/create', [RequestController::class, 'create'])->name('create');
        Route::post('/', [RequestController::class, 'store'])->name('store');
        Route::get('/data/table', [RequestController::class, 'datatable'])->name('datatable');
        Route::post('/filters', [RequestController::class, 'filters'])->name('filters');
        Route::get('/search-clients', [RequestController::class, 'searchClients'])->name('search-clients');
        Route::post('/store-client', [RequestController::class, 'storeClient'])->name('store-client');
        Route::post('/bulk-action', [RequestController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/{request}/workspace-status', [RequestController::class, 'updateWorkspaceStatus'])->name('workspace-status');
        Route::post('/{request}/proposals', [RequestController::class, 'storeProposal'])->name('proposals.store');
        Route::patch('/{request}/proposals/{quotation}/status', [RequestController::class, 'updateProposalStatus'])->name('proposals.status');
        Route::post('/{request}/proposals/{quotation}/duplicate', [RequestController::class, 'duplicateProposal'])->name('proposals.duplicate');
        Route::delete('/{request}/proposals/{quotation}', [RequestController::class, 'deleteProposal'])->name('proposals.destroy');
        Route::get('/export/csv', [RequestController::class, 'exportCsv'])->name('export-csv');
        Route::get('/export/excel', [RequestController::class, 'exportExcel'])->name('export-excel');
        Route::get('/stats', [RequestController::class, 'stats'])->name('stats');

        // Wildcard routes {request}
        Route::get('/{request}', [RequestController::class, 'show'])->name('show');
        Route::get('/{request}/edit', [RequestController::class, 'edit'])->name('edit');
        Route::put('/{request}', [RequestController::class, 'update'])->name('update');
        Route::delete('/{request}', [RequestController::class, 'destroy'])->name('destroy');
        Route::post('/{request}/restore', [RequestController::class, 'restore'])->name('restore');
        Route::put('/{request}/status', [RequestController::class, 'updateStatus'])->name('update-status');
        Route::put('/{request}/rating', [RequestController::class, 'updateRating'])->name('update-rating');
        Route::put('/{request}/flag', [RequestController::class, 'updateFlag'])->name('update-flag');
        Route::post('/{request}/notes', [RequestController::class, 'addNote'])->name('notes.store');
        Route::get('/{request}/notes', [RequestController::class, 'getNotes'])->name('notes.index');
        Route::post('/{request}/tasks', [RequestController::class, 'addTask'])->name('tasks.store');
        Route::put('/{request}/tasks/{task}/complete', [RequestController::class, 'completeTask'])->name('tasks.complete');
        Route::get('/{request}/tasks', [RequestController::class, 'getTasks'])->name('tasks.index');
        Route::post('/{request}/followups', [RequestController::class, 'addFollowup'])->name('followups.store');
        Route::post('/{request}/files', [RequestController::class, 'uploadFile'])->name('files.store');
        Route::get('/{request}/timeline', [RequestController::class, 'getTimeline'])->name('timeline');
        Route::post('/{request}/convert-to-quote', [RequestController::class, 'convertToQuote'])->name('convert-to-quote');
        Route::post('/{request}/update-template', [RequestController::class, 'updateTemplate'])->name('update-template');
    });

    Route::get('/translations', [AdminController::class, 'translations'])->name('translations');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{conversation}/reply', [ChatController::class, 'reply'])->name('chat.reply');
    Route::post('/chat/{conversation}/close', [ChatController::class, 'close'])->name('chat.close');
    Route::get('/executive-dashboard', [DashboardController::class, 'index'])->name('executive-dashboard');

    // ==================== ACTIVITIES (V2) ====================
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityV2Controller::class, 'index'])->name('index');
        Route::get('/create', [ActivityV2Controller::class, 'create'])->name('create');
        Route::post('/', [ActivityV2Controller::class, 'store'])->name('store');
        Route::get('/{activity}', [ActivityV2Controller::class, 'show'])->name('show');
        Route::get('/{activity}/edit', [ActivityV2Controller::class, 'edit'])->name('edit');
        Route::put('/{activity}', [ActivityV2Controller::class, 'update'])->name('update');
        Route::delete('/{activity}', [ActivityV2Controller::class, 'destroy'])->name('destroy');
        Route::post('/{activity}/duplicate', [ActivityV2Controller::class, 'duplicate'])->name('duplicate');
        Route::get('/{activity}/preview', [ActivityV2Controller::class, 'preview'])->name('preview');
        // Translations
        Route::post('/{activity}/translations', [ActivityV2Controller::class, 'storeTranslation'])->name('translations.store');
        // Prices
        Route::post('/{activity}/prices', [ActivityV2Controller::class, 'storePrice'])->name('prices.store');
        Route::delete('/{activity}/prices/{price}', [ActivityV2Controller::class, 'destroyPrice'])->name('prices.destroy');
        // Seasons
        Route::post('/{activity}/seasons', [ActivityV2Controller::class, 'storeSeason'])->name('seasons.store');
        Route::delete('/{activity}/seasons/{season}', [ActivityV2Controller::class, 'destroySeason'])->name('seasons.destroy');
        // Payment Scheme
        Route::get('/{activity}/payment-scheme', [ActivityV2Controller::class, 'editPaymentScheme'])->name('payment-scheme.edit');
        Route::put('/{activity}/payment-scheme', [ActivityV2Controller::class, 'updatePaymentScheme'])->name('payment-scheme.update');
        // Suppliers
        Route::post('/{activity}/suppliers', [ActivityV2Controller::class, 'syncSuppliers'])->name('suppliers.sync');
        // Publishing
        Route::post('/{activity}/publish', [ActivityV2Controller::class, 'togglePublish'])->name('publish');
    });
    Route::resource('activity-categories', ActivityCategoryController::class)->except(['show']);

    // ==================== ACCOMMODATIONS (V2) ====================
    Route::prefix('accommodations-v2')->name('accommodations-v2.')->group(function () {
        Route::get('/', [AccommodationV2Controller::class, 'index'])->name('index');
        Route::get('/create', [AccommodationV2Controller::class, 'create'])->name('create');
        Route::post('/', [AccommodationV2Controller::class, 'store'])->name('store');
        Route::get('/{accommodation}/edit', [AccommodationV2Controller::class, 'edit'])->name('edit');
        Route::put('/{accommodation}', [AccommodationV2Controller::class, 'update'])->name('update');
        Route::delete('/{accommodation}', [AccommodationV2Controller::class, 'destroy'])->name('destroy');
        Route::post('/{accommodation}/publish', [AccommodationV2Controller::class, 'togglePublish'])->name('publish');
        // Rooms
        Route::post('/{accommodation}/rooms', [AccommodationV2Controller::class, 'storeRoom'])->name('rooms.store');
        Route::delete('/{accommodation}/rooms/{room}', [AccommodationV2Controller::class, 'destroyRoom'])->name('rooms.destroy');
        // Rates
        Route::post('/{accommodation}/rooms/{room}/rates', [AccommodationV2Controller::class, 'storeRate'])->name('rates.store');
        Route::delete('/{accommodation}/rooms/{room}/rates/{rate}', [AccommodationV2Controller::class, 'destroyRate'])->name('rates.destroy');
    });

    // ==================== SUPPLIERS ====================
    Route::resource('suppliers', SupplierController::class);

    // ==================== LOCATIONS ====================
    Route::resource('countries', LocationController::class)->except(['show']);
    Route::post('/countries/{country}/regions', [LocationController::class, 'storeRegion'])->name('countries.regions.store');
    Route::put('/countries/{country}/regions/{region}', [LocationController::class, 'updateRegion'])->name('countries.regions.update');
    Route::delete('/countries/{country}/regions/{region}', [LocationController::class, 'destroyRegion'])->name('countries.regions.destroy');

    // ==================== LEADS / CRM (V2) ====================
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadV2Controller::class, 'index'])->name('index');
        Route::get('/{lead}', [LeadV2Controller::class, 'show'])->name('show');
        Route::put('/{lead}', [LeadV2Controller::class, 'update'])->name('update');
        Route::delete('/{lead}', [LeadV2Controller::class, 'destroy'])->name('destroy');
        Route::post('/{lead}/convert', [LeadV2Controller::class, 'convert'])->name('convert');
        Route::post('/{lead}/assign', [LeadV2Controller::class, 'assign'])->name('assign');
        // Conversations
        Route::get('/{lead}/conversations', [LeadV2Controller::class, 'conversations'])->name('conversations');
        Route::post('/{lead}/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    });

    // ==================== BOOKINGS ====================
    Route::resource('bookings', BookingController::class);

    // ==================== PAYMENTS ====================
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/bookings/{booking}', [PaymentController::class, 'store'])->name('store');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        // Payment Links
        Route::post('/bookings/{booking}/links', [PaymentLinkController::class, 'store'])->name('links.store');
        Route::get('/links/{token}', [PaymentLinkController::class, 'show'])->name('links.show')->withoutMiddleware('auth');
        Route::post('/links/{token}/pay', [PaymentLinkController::class, 'pay'])->name('links.pay')->withoutMiddleware('auth');
    });

    // ==================== ITINERARY TEMPLATES ====================
    Route::prefix('itinerary-templates')->name('itinerary-templates.')->group(function () {
        Route::get('/', [ItineraryTemplateController::class, 'index'])->name('index');
        Route::get('/create', [ItineraryTemplateController::class, 'create'])->name('create');
        Route::post('/', [ItineraryTemplateController::class, 'store'])->name('store');
        Route::get('/search', [ItineraryTemplateController::class, 'search'])->name('search');
        Route::get('/{template}/edit', [ItineraryTemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [ItineraryTemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [ItineraryTemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/restore', [ItineraryTemplateController::class, 'restore'])->name('restore');
        Route::post('/{template}/duplicate', [ItineraryTemplateController::class, 'duplicate'])->name('duplicate');
        Route::get('/{template}/days', [ItineraryTemplateController::class, 'getDays'])->name('days');
        Route::get('/{template}/preview', [ItineraryTemplateController::class, 'previewProposal'])->name('preview');
        Route::get('/{template}/pdf', [ItineraryTemplateController::class, 'generatePdf'])->name('pdf');
        Route::get('/{template}', [ItineraryTemplateController::class, 'show'])->name('show');
        Route::prefix('proposals')->name('proposals.')->group(function () {
            Route::get('/{template}/preview', [ItineraryTemplateController::class, 'previewProposal'])->name('preview');
            Route::get('/{template}/pdf', [ItineraryTemplateController::class, 'generateProposalPdf'])->name('pdf');
        });
    });

    // ==================== DESTINATIONS ====================
    Route::get('/destinations/data', [ItineraryTemplateController::class, 'destinations'])->name('destinations.data');
    Route::get('/hotels/data', [ItineraryTemplateController::class, 'hotels'])->name('hotels.data');
    Route::get('/activities/data', [ItineraryTemplateController::class, 'activities'])->name('activities.data');

    // ==================== ITINERARY BUILDER ====================
    Route::prefix('itinerary-builder')->name('itinerary-builder.')->group(function () {
        Route::get('/', [ItineraryBuilderController::class, 'index'])->name('index');
        Route::get('/create', [ItineraryBuilderController::class, 'create'])->name('create');
        Route::post('/', [ItineraryBuilderController::class, 'store'])->name('store');
        Route::get('/{itinerary}', [ItineraryBuilderController::class, 'show'])->name('show');
        Route::get('/{itinerary}/edit', [ItineraryBuilderController::class, 'edit'])->name('edit');
        Route::put('/{itinerary}', [ItineraryBuilderController::class, 'update'])->name('update');
        Route::delete('/{itinerary}', [ItineraryBuilderController::class, 'destroy'])->name('destroy');
        Route::post('/{itinerary}/days', [ItineraryBuilderController::class, 'storeDay'])->name('days.store');
        Route::put('/{itinerary}/days/{day}', [ItineraryBuilderController::class, 'updateDay'])->name('days.update');
        Route::delete('/{itinerary}/days/{day}', [ItineraryBuilderController::class, 'destroyDay'])->name('days.destroy');
        Route::post('/{itinerary}/days/reorder', [ItineraryBuilderController::class, 'reorderDays'])->name('days.reorder');
        Route::post('/{itinerary}/publish', [ItineraryBuilderController::class, 'togglePublish'])->name('publish');
    });

    // ==================== CMS ====================
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/', [CmsController::class, 'index'])->name('index');
        Route::get('/home-settings', [CmsController::class, 'homeSettings'])->name('home-settings');
        Route::put('/home-settings', [CmsController::class, 'updateHomeSettings'])->name('home-settings.update');
        Route::get('/content/{section}', [CmsController::class, 'content'])->name('content.edit');
        Route::put('/content/{section}', [CmsController::class, 'updateContent'])->name('content.update');
        Route::get('/pages/create', [CmsController::class, 'create'])->name('pages.create');
        Route::post('/pages', [CmsController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [CmsController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [CmsController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [CmsController::class, 'destroy'])->name('pages.destroy');
        Route::post('/pages/{page}/publish', [CmsController::class, 'togglePublish'])->name('pages.publish');
    });

    // ==================== REPORTS ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/bookings', [ReportController::class, 'bookings'])->name('bookings');
        Route::get('/suppliers', [ReportController::class, 'suppliers'])->name('suppliers');
        Route::get('/activities', [ReportController::class, 'activities'])->name('activities');
        Route::get('/weekly', [ReportController::class, 'weekly'])->name('weekly');
        Route::get('/weekly/export/{format}', [ReportController::class, 'exportWeekly'])->name('weekly.export');
        Route::get('/kpi', [ReportController::class, 'kpi'])->name('kpi');
    });

    // ==================== AUDIT LOG ====================
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // ==================== LEGACY ROUTES (keep for BC) ====================
    Route::get('/leads/old', [LeadController::class, 'index'])->name('legacy-leads.index');
    Route::put('/leads/old/{lead}', [LeadController::class, 'update'])->name('legacy-leads.update');
    Route::post('/leads/old/{lead}/convert', [LeadController::class, 'convert'])->name('legacy-leads.convert');

    Route::get('/accommodations', [AccommodationController::class, 'index'])->name('accommodations.index');
    Route::get('/accommodations/create', [AccommodationController::class, 'create'])->name('accommodations.create');
    Route::post('/accommodations', [AccommodationController::class, 'store'])->name('accommodations.store');
    Route::get('/accommodations/compare', [AccommodationController::class, 'compare'])->name('accommodations.compare');
    Route::get('/accommodations/{hotel}/edit', [AccommodationController::class, 'edit'])->name('accommodations.edit');
    Route::put('/accommodations/{hotel}', [AccommodationController::class, 'update'])->name('accommodations.update');
    Route::delete('/accommodations/{hotel}', [AccommodationController::class, 'destroy'])->name('accommodations.destroy');
    Route::post('/accommodations/{hotel}/rooms', [AccommodationController::class, 'storeRoom'])->name('accommodations.rooms.store');
    Route::delete('/accommodations/{hotel}/rooms/{room}', [AccommodationController::class, 'destroyRoom'])->name('accommodations.rooms.destroy');
    Route::post('/accommodations/{hotel}/rooms/{room}/rates', [AccommodationController::class, 'storeRate'])->name('accommodations.rates.store');
    Route::delete('/accommodations/{hotel}/rooms/{room}/rates/{rate}', [AccommodationController::class, 'destroyRate'])->name('accommodations.rates.destroy');

    Route::get('/activities-old', [ActivityController::class, 'index'])->name('legacy-activities.index');
    Route::post('/activities-old', [ActivityController::class, 'store'])->name('legacy-activities.store');
    Route::put('/activities-old/{activity}', [ActivityController::class, 'update'])->name('legacy-activities.update');
    Route::delete('/activities-old/{activity}', [ActivityController::class, 'destroy'])->name('legacy-activities.destroy');

    Route::get('/flights', [FlightBookingController::class, 'index'])->name('flights.index');
    Route::post('/flights', [FlightBookingController::class, 'store'])->name('flights.store');
    Route::put('/flights/{flight}', [FlightBookingController::class, 'update'])->name('flights.update');
    Route::delete('/flights/{flight}', [FlightBookingController::class, 'destroy'])->name('flights.destroy');

    Route::get('/itineraries', [ItineraryController::class, 'index'])->name('itineraries.index');
    Route::get('/itineraries/create', [ItineraryController::class, 'create'])->name('itineraries.create');
    Route::post('/itineraries', [ItineraryController::class, 'store'])->name('itineraries.store');
    Route::get('/itineraries/{itinerary}', [ItineraryController::class, 'show'])->name('itineraries.show');
    Route::get('/itineraries/{itinerary}/edit', [ItineraryController::class, 'edit'])->name('itineraries.edit');
    Route::put('/itineraries/{itinerary}', [ItineraryController::class, 'update'])->name('itineraries.update');
    Route::delete('/itineraries/{itinerary}', [ItineraryController::class, 'destroy'])->name('itineraries.destroy');
    Route::post('/itineraries/{itinerary}/duplicate', [ItineraryController::class, 'duplicate'])->name('itineraries.duplicate');
    Route::get('/itineraries/{itinerary}/pdf', [ItineraryController::class, 'downloadPdf'])->name('itineraries.pdf');
    Route::post('/itineraries/{itinerary}/days', [ItineraryController::class, 'storeDay'])->name('itineraries.days.store');
    Route::put('/itineraries/{itinerary}/days/{day}', [ItineraryController::class, 'updateDay'])->name('itineraries.days.update');
    Route::delete('/itineraries/{itinerary}/days/{day}', [ItineraryController::class, 'destroyDay'])->name('itineraries.days.destroy');
    Route::post('/itineraries/{itinerary}/images', [ItineraryController::class, 'storeImages'])->name('itineraries.images.store');
    Route::delete('/itineraries/{itinerary}/images/{image}', [ItineraryController::class, 'destroyImage'])->name('itineraries.images.destroy');
    Route::post('/itineraries/{itinerary}/images/{image}/cover', [ItineraryController::class, 'setCover'])->name('itineraries.images.cover');

    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/proposal-planning', [ProposalPlanningController::class, 'index'])->name('proposal-planning.index');
    Route::get('/proposal-planning/export', [ProposalPlanningController::class, 'export'])->name('proposal-planning.export');
    Route::post('/proposal-planning/{quotation}/advance', [ProposalPlanningController::class, 'advance'])->name('proposal-planning.advance');
    Route::post('/proposal-planning/{quotation}/toggle', [ProposalPlanningController::class, 'toggle'])->name('proposal-planning.toggle');
    Route::put('/proposal-planning/{quotation}/note', [ProposalPlanningController::class, 'note'])->name('proposal-planning.note');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'downloadPdf'])->name('quotations.pdf');
    Route::put('/quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    Route::put('/quotations/{quotation}/days/{day}', [QuotationController::class, 'updateDay'])->name('quotations.days.update');
    Route::post('/quotations/{quotation}/days/{day}/items', [QuotationController::class, 'storeItem'])->name('quotations.items.store');
    Route::delete('/quotations/{quotation}/days/{day}/items/{item}', [QuotationController::class, 'destroyItem'])->name('quotations.items.destroy');
    Route::post('/quotations/{quotation}/payments', [QuotationController::class, 'storePayment'])->name('quotations.payments.store');
    Route::delete('/quotations/{quotation}/payments/{payment}', [QuotationController::class, 'destroyPayment'])->name('quotations.payments.destroy');
    Route::post('/quotations/{quotation}/expenses', [QuotationController::class, 'storeExpense'])->name('quotations.expenses.store');
    Route::delete('/quotations/{quotation}/expenses/{expense}', [QuotationController::class, 'destroyExpense'])->name('quotations.expenses.destroy');
    Route::post('/quotations/{quotation}/emails/ready-to-book', [QuotationController::class, 'sendReadyToBook'])->name('quotations.emails.ready_to_book');
    Route::post('/quotations/{quotation}/emails/pre-confirmation', [QuotationController::class, 'sendPreConfirmation'])->name('quotations.emails.pre_confirmation');
    Route::post('/quotations/{quotation}/emails/confirmation', [QuotationController::class, 'sendConfirmation'])->name('quotations.emails.confirmation');

    Route::get('/mail/settings', [MailSettingsController::class, 'show'])->name('mail.settings');
    Route::put('/mail/settings', [MailSettingsController::class, 'update'])->name('mail.settings.update');
    Route::post('/mail/settings/test', [MailSettingsController::class, 'test'])->name('mail.settings.test');

    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('incoming', [IncomingMailController::class, 'accounts'])->name('incoming.accounts');
        Route::post('incoming', [IncomingMailController::class, 'storeAccount'])->name('incoming.store');
        Route::put('incoming/{account}', [IncomingMailController::class, 'updateAccount'])->name('incoming.update');
        Route::delete('incoming/{account}', [IncomingMailController::class, 'destroyAccount'])->name('incoming.destroy');
        Route::post('incoming/fetch', [IncomingMailController::class, 'fetchNow'])->name('incoming.fetch');
        Route::get('inbox', [IncomingMailController::class, 'inbox'])->name('inbox');
        Route::get('inbox/{email}', [IncomingMailController::class, 'show'])->name('inbox.show');
        Route::post('inbox/{email}/convert', [IncomingMailController::class, 'convert'])->name('inbox.convert');
        Route::post('inbox/{email}/ignore', [IncomingMailController::class, 'ignore'])->name('inbox.ignore');
    });
    Route::post('/quotations/{quotation}/snapshots', [ProposalWorkspaceController::class, 'snapshot'])->name('quotations.snapshots.store');
    Route::put('/quotations/{quotation}/travel-information', [ProposalWorkspaceController::class, 'travelInfoUpdate'])->name('quotations.travel-information.update');
    Route::post('/quotations/{quotation}/travelers', [ProposalWorkspaceController::class, 'travelerStore'])->name('quotations.travelers.store');
    Route::delete('/quotations/{quotation}/travelers/{traveler}', [ProposalWorkspaceController::class, 'travelerDestroy'])->name('quotations.travelers.destroy');
    Route::post('/quotations/{quotation}/adjustments', [ProposalWorkspaceController::class, 'adjustmentStore'])->name('quotations.adjustments.store');
    Route::delete('/quotations/{quotation}/adjustments/{adjustment}', [ProposalWorkspaceController::class, 'adjustmentDestroy'])->name('quotations.adjustments.destroy');
    Route::post('/quotations/{quotation}/documents', [ProposalWorkspaceController::class, 'documentStore'])->name('quotations.documents.store');
    Route::get('/quotations/{quotation}/documents/{document}', [ProposalWorkspaceController::class, 'documentDownload'])->name('quotations.documents.download');
    Route::delete('/quotations/{quotation}/documents/{document}', [ProposalWorkspaceController::class, 'documentDestroy'])->name('quotations.documents.destroy');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reservations/{reservation}/email', [ReservationController::class, 'email'])->name('reservations.email');
    Route::post('/quotations/{quotation}/reservation-mails', [ReservationController::class, 'emailAll'])->name('quotations.reservation-mails.send');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
        Route::get('/overview', [EvaluationController::class, 'overview'])->name('overview');
        Route::get('/duplicates', [EvaluationController::class, 'detectDuplicates'])->name('duplicates');
        Route::post('/assign', [EvaluationController::class, 'assignInvoice'])->name('assign');
        Route::get('/reservation-invoices', [EvaluationController::class, 'invoices'])->name('invoices');
        Route::post('/reservation-invoices', [EvaluationController::class, 'uploadDocument'])->name('invoices.upload');
        Route::get('/invoices/{invoice}/download', [EvaluationController::class, 'downloadInvoice'])->name('invoices.download');
        Route::post('/invoices/{invoice}/split', [EvaluationController::class, 'splitInvoice'])->name('invoices.split');
        Route::put('/invoices/{invoice}', [EvaluationController::class, 'updateInvoice'])->name('invoices.update');
        Route::put('/invoices/{invoice}/status', [EvaluationController::class, 'updateInvoiceStatus'])->name('invoices.status');
        Route::put('/entries/{entry}', [EvaluationController::class, 'updateEntry'])->name('entries.update');
        Route::get('/{quotation}', [EvaluationController::class, 'show'])->name('show');
        Route::get('/{quotation}/missing', [EvaluationController::class, 'missingInvoices'])->name('missing');
        Route::get('/{quotation}/audit', [EvaluationController::class, 'auditLog'])->name('audit');
        Route::get('/{quotation}/export', [EvaluationController::class, 'exportCsv'])->name('export');
        Route::post('/{quotation}/invoices', [EvaluationController::class, 'storeInvoice'])->name('invoices.store');
        Route::post('/{quotation}/approve', [EvaluationController::class, 'approve'])->name('approve');
    });

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/manage-2fa', [TwoFactorController::class, 'index'])->name('two-factor.index');
    Route::post('/manage-2fa/start', [TwoFactorController::class, 'start'])->name('two-factor.start');
    Route::post('/manage-2fa/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('/manage-2fa/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    Route::get('/records/{slug}', [ModuleRecordController::class, 'index'])->name('records.index');
    Route::post('/records/{slug}', [ModuleRecordController::class, 'store'])->name('records.store');
    Route::put('/records/{slug}/{record}', [ModuleRecordController::class, 'update'])->name('records.update');
    Route::delete('/records/{slug}/{record}', [ModuleRecordController::class, 'destroy'])->name('records.destroy');

    Route::get('/module/{slug}', [AdminController::class, 'module'])->name('module');
});

// ==================== PAYMENT CALLBACKS & WEBHOOKS ====================
Route::get('/payments/callback/{gateway}', [PaymentWebhookController::class, 'callback'])->name('payments.callback');
Route::post('/payments/webhook/stripe', [PaymentWebhookController::class, 'webhookStripe'])->name('payments.webhook.stripe');
Route::post('/payments/webhook/flutterwave', [PaymentWebhookController::class, 'webhookFlutterwave'])->name('payments.webhook.flutterwave');
