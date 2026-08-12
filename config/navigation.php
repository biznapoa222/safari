<?php

return [
    ['label' => 'Home', 'icon' => 'house', 'route' => 'admin.dashboard'],
    ['label' => 'Insights', 'icon' => 'chart-no-axes-combined', 'route' => 'admin.executive-dashboard'],
    ['label' => 'Travel', 'icon' => 'briefcase-business', 'children' => [
        'Requests',
        'Activities',
        'Accommodations',
        'Facilities',
        'Itineraries',
        'Itineraries Groups',
        'Proposal Planning',
        'Flight Ticket Requests',
    ]],
    ['label' => 'Operations', 'icon' => 'bus-front', 'children' => [
        'Payment Deadlines',
        'Daily Movements',
        'Double Checks',
    ]],
    ['label' => 'Destination', 'icon' => 'map', 'children' => [
        'Countries',
        'Locations',
        'Travel Trajectories',
        'Guides',
        'Vehicles',
        'Suppliers',
    ]],
    ['label' => 'Fees & Fares', 'icon' => 'badge-dollar-sign', 'children' => [
        'National Parks',
        'Taxes',
        'Jeeps',
        'Flights',
        'Transfers',
        'Supplements',
    ]],
    ['label' => 'Marketing', 'icon' => 'megaphone', 'children' => [
        'Discounts',
        'Marketing Performances',
    ]],
    ['label' => 'Mobile App', 'icon' => 'smartphone', 'children' => [
        'Accounts',
        'Notifications',
    ]],
    ['label' => 'Administration', 'icon' => 'settings', 'children' => [
        'Margins',
        'Margin Percentages',
        'Exchange Rates',
        'Payment Methods',
        'Invoices',
        'BBA',
        'Manage 2FA',
        'Reservations Mails',
        'Companies',
        'Roles',
        'Users',
    ]],
    ['label' => 'Content', 'icon' => 'badge-dollar-sign', 'children' => [
        'Languages',
        'Mails',
        'Mail Contents',
        'Automated Mails',
    ]],
];
