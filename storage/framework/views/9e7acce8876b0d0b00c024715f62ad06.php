<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($template->trip_name ?? $template->name); ?> – Safari Proposal</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Montserrat:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        
        :root {
            --page-bg: #080907;
            --panel-bg: #10110e;
            --dark-green: #10291d;
            --gold: #a58b3e;
            --light-gold: #c0a75a;
            --text-primary: #e6dfcf;
            --text-secondary: #aaa18e;
            --border-gold: rgba(165, 139, 62, 0.65);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        img { max-width: 100%; height: auto; }
        body {
            font-family: 'Cormorant Garamond', 'Georgia', serif;
            background: var(--page-bg);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @page { size: A4 portrait; margin: 0; }
        .page {
            width: 210mm; min-height: 297mm;
            padding: 0; position: relative;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
        }
        .page:last-child { page-break-after: avoid; break-after: auto; }
        .avoid-break { break-inside: avoid; page-break-inside: avoid; }

        
        .cover-page { position: relative; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
        .cover-bg { position: absolute; inset: 0; }
        .cover-bg img { width: 100%; height: 100%; object-fit: cover; }
        .cover-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(8,9,7,.3) 0%, rgba(8,9,7,.85) 60%, #080907 100%); }
        .cover-content { position: relative; z-index: 2; padding: 60mm 30mm 20mm; }
        .cover-label { font-family: 'Montserrat', sans-serif; font-size: 9px; letter-spacing: 4px; text-transform: uppercase; color: var(--gold); margin-bottom: 12px; }
        .cover-title { font-family: 'Cinzel', serif; font-size: 36px; color: #fff; line-height: 1.15; margin-bottom: 8px; letter-spacing: 1px; }
        .cover-subtitle { font-size: 18px; color: var(--light-gold); font-style: italic; margin-bottom: 20px; }
        .cover-destinations { font-family: 'Montserrat', sans-serif; font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 24px; }
        .cover-client { font-size: 14px; color: var(--text-secondary); margin-bottom: 6px; }
        .cover-dates { font-size: 12px; color: var(--gold); margin-bottom: 30px; }
        .cover-gold-line { width: 60px; height: 1px; background: var(--gold); margin: 0 auto 24px; }

        
        .metrics { display: flex; justify-content: center; gap: 30px; margin-top: 20px; flex-wrap: wrap; }
        .metric { text-align: center; }
        .metric-value { font-family: 'Cinzel', serif; font-size: 22px; color: var(--light-gold); }
        .metric-label { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 2px; text-transform: uppercase; color: var(--text-secondary); margin-top: 4px; }

        
        .letter-page { padding: 30mm 25mm; }
        .letter-label { font-family: 'Montserrat', sans-serif; font-size: 8px; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 20px; }
        .letter-salutation { font-size: 16px; color: var(--text-primary); margin-bottom: 16px; }
        .letter-body { font-size: 11px; color: var(--text-secondary); line-height: 1.8; margin-bottom: 16px; }
        .letter-closing { font-size: 11px; color: var(--text-secondary); margin-top: 24px; }
        .letter-consultant { margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-gold); }
        .letter-consultant strong { color: var(--light-gold); font-size: 13px; }
        .letter-consultant span { display: block; font-size: 9px; color: var(--text-secondary); }

        
        .section-header { padding: 20mm 25mm 10mm; }
        .section-label { font-family: 'Montserrat', sans-serif; font-size: 8px; letter-spacing: 4px; text-transform: uppercase; color: var(--gold); }
        .section-title { font-family: 'Cinzel', serif; font-size: 28px; color: #fff; margin-top: 6px; }
        .section-line { width: 40px; height: 1px; background: var(--gold); margin-top: 12px; }

        
        .schedule-table { width: 100%; border-collapse: collapse; margin: 10mm 25mm; }
        .schedule-table th { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); padding: 10px 8px; border-bottom: 1px solid var(--border-gold); text-align: left; }
        .schedule-table td { font-size: 10px; padding: 10px 8px; border-bottom: 1px solid rgba(165,139,62,.2); color: var(--text-primary); }
        .schedule-table tr:last-child td { border-bottom: none; }

        
        .highlights-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 5mm 25mm; }
        .highlight-card { background: var(--panel-bg); border: 1px solid var(--border-gold); padding: 16px; border-radius: 4px; }
        .highlight-icon { color: var(--light-gold); font-size: 18px; margin-bottom: 8px; }
        .highlight-title { font-family: 'Montserrat', sans-serif; font-size: 8px; letter-spacing: 2px; text-transform: uppercase; color: var(--light-gold); margin-bottom: 4px; }
        .highlight-desc { font-size: 9px; color: var(--text-secondary); line-height: 1.5; }

        
        .day-hero { position: relative; height: 120mm; }
        .day-hero img { width: 100%; height: 100%; object-fit: cover; }
        .day-hero-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(8,9,7,.2) 0%, rgba(8,9,7,.8) 70%, #080907 100%); }
        .day-hero-content { position: absolute; bottom: 30mm; left: 25mm; right: 25mm; z-index: 2; }
        .day-number-badge { font-family: 'Cinzel', serif; font-size: 48px; color: var(--gold); line-height: 1; }
        .day-destination-name { font-family: 'Cinzel', serif; font-size: 28px; color: #fff; margin-top: 4px; }
        .day-date-label { font-family: 'Montserrat', sans-serif; font-size: 8px; letter-spacing: 3px; text-transform: uppercase; color: var(--text-secondary); margin-top: 6px; }
        .day-info { padding: 10mm 25mm; }
        .day-description { font-size: 11px; color: var(--text-secondary); line-height: 1.8; margin-bottom: 16px; }

        
        .activity-list { list-style: none; padding: 0; }
        .activity-item { display: flex; gap: 8px; padding: 6px 0; border-bottom: 1px solid rgba(165,139,62,.1); }
        .activity-time { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 1px; color: var(--gold); min-width: 50px; margin-top: 2px; }
        .activity-name { font-size: 10px; color: var(--text-primary); }
        .activity-desc { font-size: 9px; color: var(--text-secondary); }

        
        .acc-card { background: var(--panel-bg); border: 1px solid var(--border-gold); padding: 16px; margin-top: 12px; border-radius: 4px; }
        .acc-card h4 { font-family: 'Cinzel', serif; font-size: 14px; color: var(--light-gold); margin-bottom: 8px; }
        .acc-card p { font-size: 9px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 4px; }
        .acc-card label { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); display: block; margin-top: 8px; }

        
        .meal-badges { display: flex; gap: 6px; flex-wrap: wrap; margin: 8px 0; }
        .meal-badge { font-family: 'Montserrat', sans-serif; font-size: 6px; letter-spacing: 1px; text-transform: uppercase; padding: 4px 8px; border: 1px solid var(--border-gold); border-radius: 2px; color: var(--light-gold); }

        
        .gallery-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; padding: 5mm 25mm; }
        .gallery-grid .featured { grid-column: 1 / -1; }
        .gallery-grid .wide { grid-column: 1 / -1; }
        .gallery-grid img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-featured { height: 120mm; }
        .gallery-supporting { height: 80mm; }
        .gallery-wide { height: 100mm; }
        .gallery-caption { font-size: 8px; color: var(--text-secondary); font-style: italic; margin-top: 2px; }

        
        .pricing-page { padding: 20mm 25mm; }
        .pricing-table { width: 100%; border-collapse: collapse; margin: 10mm 0; }
        .pricing-table th { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); padding: 10px 8px; border-bottom: 1px solid var(--border-gold); text-align: left; }
        .pricing-table td { font-size: 10px; padding: 10px 8px; border-bottom: 1px solid rgba(165,139,62,.2); color: var(--text-primary); }
        .pricing-table .total-row td { border-top: 2px solid var(--gold); font-weight: 700; color: var(--light-gold); }
        .pricing-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 10mm 0; }
        .pricing-card { background: var(--panel-bg); border: 1px solid var(--border-gold); padding: 14px; text-align: center; border-radius: 4px; }
        .pricing-card strong { font-family: 'Cinzel', serif; font-size: 18px; color: var(--light-gold); display: block; }
        .pricing-card span { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 1px; text-transform: uppercase; color: var(--text-secondary); }

        
        .inclusion-list { list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .inclusion-list li { font-size: 10px; color: var(--text-secondary); padding: 4px 0; }
        .inclusion-list li:before { content: '\2713'; color: var(--gold); margin-right: 8px; }
        .exclusion-list li:before { content: '\2717'; color: #c0392b; margin-right: 8px; }

        
        .acceptance-section { padding: 20mm 25mm; text-align: center; }
        .acceptance-title { font-family: 'Cinzel', serif; font-size: 24px; color: #fff; margin-bottom: 12px; }
        .acceptance-text { font-size: 11px; color: var(--text-secondary); margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto; }
        .acceptance-buttons { display: flex; gap: 12px; justify-content: center; }
        .btn-gold { font-family: 'Montserrat', sans-serif; font-size: 9px; letter-spacing: 2px; text-transform: uppercase; padding: 12px 28px; background: var(--gold); color: #080907; border: none; border-radius: 3px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-outline { font-family: 'Montserrat', sans-serif; font-size: 9px; letter-spacing: 2px; text-transform: uppercase; padding: 12px 28px; background: transparent; color: var(--gold); border: 1px solid var(--border-gold); border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }

        
        .company-page { padding: 20mm 25mm; text-align: center; }
        .company-logo { max-width: 120px; margin-bottom: 16px; }
        .company-name { font-family: 'Cinzel', serif; font-size: 22px; color: #fff; margin-bottom: 4px; }
        .company-tagline { font-size: 12px; color: var(--light-gold); font-style: italic; margin-bottom: 20px; }
        .company-stats { display: flex; justify-content: center; gap: 30px; margin: 20px 0; }
        .company-stat { text-align: center; }
        .company-stat strong { font-family: 'Cinzel', serif; font-size: 28px; color: var(--light-gold); display: block; }
        .company-stat span { font-family: 'Montserrat', sans-serif; font-size: 7px; letter-spacing: 1px; text-transform: uppercase; color: var(--text-secondary); }
        .testimonial { background: var(--panel-bg); border: 1px solid var(--border-gold); padding: 16px; margin: 10px 0; border-radius: 4px; text-align: left; }
        .testimonial p { font-size: 10px; color: var(--text-secondary); font-style: italic; line-height: 1.6; }
        .testimonial strong { font-size: 9px; color: var(--light-gold); display: block; margin-top: 8px; }
        .contact-info { font-size: 9px; color: var(--text-secondary); margin-top: 16px; }

        
        .page-header { position: absolute; top: 0; left: 0; right: 0; padding: 8mm 25mm 4mm; display: flex; justify-content: space-between; font-family: 'Montserrat', sans-serif; font-size: 6px; letter-spacing: 2px; text-transform: uppercase; color: var(--text-secondary); border-bottom: 1px solid var(--border-gold); }
        .page-footer { position: absolute; bottom: 0; left: 0; right: 0; padding: 4mm 25mm 8mm; display: flex; justify-content: space-between; font-family: 'Montserrat', sans-serif; font-size: 6px; letter-spacing: 1px; color: var(--text-secondary); border-top: 1px solid rgba(165,139,62,.2); }

        
        .corner-tl { position: absolute; top: 10mm; left: 10mm; width: 15mm; height: 1px; background: var(--gold); transform: rotate(-45deg); transform-origin: left top; }
        .corner-tr { position: absolute; top: 10mm; right: 10mm; width: 15mm; height: 1px; background: var(--gold); transform: rotate(45deg); transform-origin: right top; }
        .corner-bl { position: absolute; bottom: 10mm; left: 10mm; width: 15mm; height: 1px; background: var(--gold); transform: rotate(45deg); transform-origin: left bottom; }
        .corner-br { position: absolute; bottom: 10mm; right: 10mm; width: 15mm; height: 1px; background: var(--gold); transform: rotate(-45deg); transform-origin: right bottom; }

        @media print {
            body { background: var(--page-bg); }
            .page { box-shadow: none; margin: 0; width: 210mm; min-height: 297mm; padding: 0; page-break-after: always; break-after: page; }
            .cover-content { padding: 60mm 30mm 20mm; }
            .letter-page { padding: 30mm 25mm; }
            .section-header { padding: 20mm 25mm 10mm; }
            .day-hero { height: 120mm; }
            .day-info { padding: 10mm 25mm; }
            .pricing-page { padding: 20mm 25mm; }
            .acceptance-section { padding: 20mm 25mm; }
            .company-page { padding: 20mm 25mm; }
            .schedule-table { margin: 10mm 25mm; }
            .day-hero-content { bottom: 30mm; left: 25mm; right: 25mm; }
            .highlights-grid { padding: 5mm 25mm; }
            .gallery-grid { padding: 5mm 25mm; }
        }
        @media screen {
            .page { width: 100%; min-height: auto; padding: 0; page-break-after: none; break-after: auto; margin: 0 auto; max-width: 1200px; box-shadow: 0 2px 20px rgba(0,0,0,0.5); margin-bottom: 24px; border-radius: 4px; }
            body { padding: 16px; }
            .cover-content { padding: 15vh 5vw 5vw; }
            .cover-title { font-size: clamp(22px, 5vw, 36px); }
            .cover-subtitle { font-size: clamp(14px, 2.5vw, 18px); }
            .cover-destinations { font-size: clamp(8px, 1.4vw, 10px); }
            .cover-client { font-size: clamp(12px, 2vw, 14px); }
            .cover-dates { font-size: clamp(10px, 1.8vw, 12px); }
            .metric-value { font-size: clamp(16px, 3vw, 22px); }
            .metric-label { font-size: clamp(6px, 1vw, 7px); }
            .letter-page { padding: 5vh 5vw; }
            .letter-body { font-size: clamp(10px, 1.6vw, 11px); }
            .letter-salutation { font-size: clamp(14px, 2.2vw, 16px); }
            .letter-closing { font-size: clamp(10px, 1.6vw, 11px); }
            .section-header { padding: 4vh 5vw 2vh; }
            .section-title { font-size: clamp(20px, 4vw, 28px); }
            .section-label { font-size: clamp(7px, 1.2vw, 8px); }
            .schedule-table { margin: 2vh 5vw; font-size: clamp(8px, 1.4vw, 10px); }
            .schedule-table th { font-size: clamp(6px, 1vw, 7px); }
            .highlights-grid { padding: 2vh 5vw; gap: clamp(8px, 2vw, 16px); }
            .highlight-title { font-size: clamp(7px, 1.2vw, 8px); }
            .highlight-desc { font-size: clamp(8px, 1.3vw, 9px); }
            .day-hero { height: clamp(200px, 40vh, 500px); }
            .day-number-badge { font-size: clamp(32px, 6vw, 48px); }
            .day-destination-name { font-size: clamp(20px, 4vw, 28px); }
            .day-date-label { font-size: clamp(7px, 1.2vw, 8px); }
            .day-hero-content { bottom: clamp(20px, 5vh, 60px); left: 5vw; right: 5vw; }
            .day-info { padding: 3vh 5vw; }
            .day-description { font-size: clamp(10px, 1.6vw, 11px); }
            .activity-name { font-size: clamp(9px, 1.4vw, 10px); }
            .activity-desc { font-size: clamp(8px, 1.3vw, 9px); }
            .activity-time { font-size: clamp(6px, 1vw, 7px); }
            .acc-card h4 { font-size: clamp(12px, 2vw, 14px); }
            .acc-card p { font-size: clamp(8px, 1.3vw, 9px); }
            .meal-badge { font-size: clamp(5px, 0.9vw, 6px); }
            .pricing-page { padding: 4vh 5vw; }
            .pricing-table { font-size: clamp(8px, 1.4vw, 10px); }
            .pricing-table th { font-size: clamp(6px, 1vw, 7px); }
            .pricing-card strong { font-size: clamp(14px, 2.5vw, 18px); }
            .pricing-card span { font-size: clamp(6px, 1vw, 7px); }
            .acceptance-section { padding: 5vh 5vw; }
            .acceptance-title { font-size: clamp(18px, 3.5vw, 24px); }
            .acceptance-text { font-size: clamp(10px, 1.5vw, 11px); }
            .btn-gold, .btn-outline { font-size: clamp(8px, 1.3vw, 9px); padding: clamp(8px, 1.5vw, 12px) clamp(16px, 3vw, 28px); }
            .company-page { padding: 4vh 5vw; }
            .company-name { font-size: clamp(18px, 3.5vw, 22px); }
            .company-tagline { font-size: clamp(11px, 1.8vw, 12px); }
            .company-stat strong { font-size: clamp(20px, 4vw, 28px); }
            .company-stat span { font-size: clamp(6px, 1vw, 7px); }
            .page-header, .page-footer { display: none; }
            .inclusion-list li { font-size: clamp(9px, 1.4vw, 10px); }
            .gallery-grid { padding: 2vh 5vw; }
            .gallery-featured { height: clamp(180px, 35vh, 400px); }
            .gallery-supporting { height: clamp(120px, 25vh, 300px); }
            .gallery-wide { height: clamp(150px, 30vh, 350px); }
            .cover-bg img, .day-hero img { width: 100%; height: 100%; object-fit: cover; }
            .letter-consultant strong { font-size: clamp(11px, 1.8vw, 13px); }
            .letter-consultant span { font-size: clamp(8px, 1.3vw, 9px); }
            .acceptance-buttons { flex-wrap: wrap; gap: clamp(8px, 2vw, 12px); }
            .company-stats { gap: clamp(12px, 4vw, 30px); flex-wrap: wrap; }
            .metrics { gap: clamp(12px, 4vw, 30px); }
            #acceptForm input, #acceptForm label, #changeForm input, #changeForm textarea { font-size: clamp(9px, 1.4vw, 10px); }
            .contact-info, .testimonial p { font-size: clamp(9px, 1.4vw, 10px); }
            .testimonial strong { font-size: clamp(8px, 1.3vw, 9px); }
        }
        @media screen and (max-width: 600px) {
            .pricing-summary { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .highlights-grid { grid-template-columns: 1fr; }
            .inclusion-list { grid-template-columns: 1fr; }
            .gallery-grid { grid-template-columns: 1fr; }
            .schedule-table { display: block; overflow-x: auto; white-space: nowrap; }
            .page { border-radius: 0; box-shadow: none; margin-bottom: 12px; }
            .cover-page { min-height: 60vh; }
            .company-stats { flex-direction: column; gap: 12px; }
            .metrics { flex-direction: column; gap: 12px; }
            .acceptance-buttons { flex-direction: column; align-items: stretch; }
            .btn-gold, .btn-outline { text-align: center; }
            .cover-content { padding: 10vh 4vw 4vw; }
        }
    </style>
</head>
<body>


<div class="page cover-page">
    <div class="cover-bg">
        <img src="<?php echo e($template->days->first()?->image ?? $template->days->first()?->destination?->hero_image ?? asset('images/safari-hero.jpg')); ?>" alt="">
    </div>
    <div class="cover-overlay"></div>
    <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
    <div class="cover-content">
        <div style="margin-bottom:30px">
            <img src="<?php echo e($agency->logo ?? ''); ?>" alt="<?php echo e($agency->name ?? ''); ?>" style="max-width:80px;margin-bottom:10px">
        </div>
        <div class="cover-label"><?php echo e($settings['cover_heading'] ?? 'A Curated Safari Experience'); ?></div>
        <h1 class="cover-title"><?php echo e($template->trip_name ?? $template->name); ?></h1>
        <div class="cover-subtitle"><?php echo e($template->days->pluck('destination.name')->unique()->implode(' · ')); ?></div>
        <div class="cover-gold-line"></div>
        <div class="cover-client">Prepared exclusively for</div>
        <div style="font-family:'Cinzel',serif;font-size:20px;color:#fff;margin-bottom:8px"><?php echo e($settings['client_name'] ?? '[Client Name]'); ?></div>
        <div class="cover-dates"><?php echo e($template->days->first()?->date?->format('d M Y') ?? '[Start Date]'); ?> – <?php echo e($template->days->last()?->date?->format('d M Y') ?? '[End Date]'); ?></div>
        <div class="metrics">
            <div class="metric">
                <div class="metric-value"><?php echo e($template->duration_days); ?> Days</div>
                <div class="metric-label">Duration</div>
            </div>
            <div class="metric">
                <div class="metric-value"><?php echo e($settings['guest_count'] ?? '2'); ?></div>
                <div class="metric-label">Guests</div>
            </div>
            <div class="metric">
                <div class="metric-value"><?php echo e($template->days->pluck('destination.name')->unique()->count()); ?></div>
                <div class="metric-label">Destinations</div>
            </div>
            <div class="metric">
                <div class="metric-value"><?php echo e($template->pricing->first()?->currency ?? 'USD'); ?> <?php echo e(number_format($template->pricing->first()?->total_cost ?? 0)); ?></div>
                <div class="metric-label">Investment</div>
            </div>
        </div>
    </div>
</div>


<div class="page letter-page">
    <div class="corner-tl"></div><div class="corner-tr"></div>
    <div class="letter-label">Personal Letter</div>
    <div class="letter-gold-line" style="width:40px;height:1px;background:var(--gold);margin-bottom:20px"></div>
    <div class="letter-salutation">Dear <?php echo e($settings['client_name'] ?? 'Guest'); ?>,</div>
    <div class="letter-body">
        <p><?php echo e($settings['personal_letter'] ?? 'Thank you for considering us to create your dream African safari. We have精心 crafted this itinerary to showcase the very best of East Africa\'s wilderness, wildlife, and wonders.'); ?></p>
        <p style="margin-top:12px">Every detail has been thoughtfully arranged to ensure your journey is seamless, luxurious, and unforgettable. From the moment you arrive until your final farewell, you will be immersed in the magic of Africa.</p>
    </div>
    <div class="letter-closing">
        <p>We look forward to welcoming you to Africa.</p>
        <p style="margin-top:12px">Warmest regards,</p>
    </div>
    <div class="letter-consultant">
        <strong><?php echo e($settings['consultant_name'] ?? 'Your Safari Consultant'); ?></strong>
        <span>Call or WhatsApp: +254 725 346 022</span>
        <span>info@shishifootsteps.com · bookings@shishifootsteps.com</span>
        <span>Office: Nairobi, Kenya</span>
    </div>
</div>


<div class="page">
    <div class="section-header">
        <div class="section-label">Your Journey</div>
        <div class="section-title">Day by Day Itinerary</div>
        <div class="section-line"></div>
    </div>

    <table class="schedule-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                <th>Destination</th>
                <th>Accommodation</th>
                <th>Room Type</th>
                <th>Meal Plan</th>
                <th>Nights</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $template->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($day->day_number); ?></td>
                <td><?php echo e($day->date?->format('d M') ?? ''); ?></td>
                <td><?php echo e($day->destination?->name ?? $day->destination ?? ''); ?></td>
                <td><?php echo e($day->hotel?->name ?? $day->hotel_name ?? ''); ?></td>
                <td><?php echo e($day->room_type ?? ''); ?></td>
                <td><?php echo e($day->meal_plan ?? ''); ?></td>
                <td>1</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div style="padding:5mm 25mm">
        <div class="section-label" style="margin-bottom:12px">Trip Highlights</div>
        <div class="highlights-grid">
            <div class="highlight-card">
                <div class="highlight-icon">&#9733;</div>
                <div class="highlight-title">Game Drives</div>
                <div class="highlight-desc">Expert-guided safari drives in open 4x4 vehicles</div>
            </div>
            <div class="highlight-card">
                <div class="highlight-icon">&#9733;</div>
                <div class="highlight-title">Premium Lodges</div>
                <div class="highlight-desc">Hand-selected luxury accommodations</div>
            </div>
            <div class="highlight-card">
                <div class="highlight-icon">&#9733;</div>
                <div class="highlight-title">All Meals Included</div>
                <div class="highlight-desc">Full-board dining with fine cuisine</div>
            </div>
        </div>
    </div>
</div>


<?php $__currentLoopData = $template->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="page">
    <div class="corner-tl"></div><div class="corner-tr"></div>
    <div class="day-hero">
        <img src="<?php echo e($day->image ?? $day->destination?->hero_image ?? asset('images/safari-hero.jpg')); ?>" alt="<?php echo e($day->destination?->name ?? ''); ?>">
        <div class="day-hero-overlay"></div>
        <div class="day-hero-content">
            <div class="day-number-badge">Day <?php echo e($day->day_number); ?></div>
            <div class="day-destination-name"><?php echo e($day->destination?->name ?? $day->destination ?? 'Safari'); ?></div>
            <div class="day-date-label"><?php echo e($day->date?->format('l, d F Y') ?? ''); ?> · 1 Night</div>
        </div>
    </div>

    <div class="day-info">
        <?php if($day->description): ?>
        <div class="day-description"><?php echo e($day->description); ?></div>
        <?php elseif($day->destination?->description): ?>
        <div class="day-description"><?php echo e($day->destination->description); ?></div>
        <?php endif; ?>

        <?php if($day->morning_activity || $day->afternoon_activity || $day->evening_activity): ?>
        <h4 style="font-family:'Montserrat',sans-serif;font-size:7px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-bottom:8px">Activities</h4>
        <div class="activity-list">
            <?php if($day->morning_activity): ?>
            <div class="activity-item"><span class="activity-time">AM</span><div><div class="activity-name"><?php echo e($day->morning_activity); ?></div></div></div>
            <?php endif; ?>
            <?php if($day->afternoon_activity): ?>
            <div class="activity-item"><span class="activity-time">PM</span><div><div class="activity-name"><?php echo e($day->afternoon_activity); ?></div></div></div>
            <?php endif; ?>
            <?php if($day->evening_activity): ?>
            <div class="activity-item"><span class="activity-time">EVE</span><div><div class="activity-name"><?php echo e($day->evening_activity); ?></div></div></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if($day->hotel || $day->hotel_name): ?>
        <div class="acc-card avoid-break">
            <h4>Accommodation</h4>
            <p><strong><?php echo e($day->hotel?->name ?? $day->hotel_name); ?></strong></p>
            <?php if($day->room_type): ?><p><label>Room Type</label> <?php echo e($day->room_type); ?></p><?php endif; ?>
            <?php if($day->hotel?->description): ?><p><?php echo e($day->hotel->description); ?></p><?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if($day->meal_plan): ?>
        <div class="meal-badges">
            <?php $__currentLoopData = explode(',', $day->meal_plan); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="meal-badge"><?php echo e(trim($meal)); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <?php if($day->included_services): ?>
        <p style="font-size:9px;color:var(--text-secondary);margin-top:8px"><strong style="color:var(--light-gold)">Included:</strong> <?php echo e($day->included_services); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="page pricing-page">
    <div class="section-header" style="padding:0 0 10mm">
        <div class="section-label">Investment</div>
        <div class="section-title">Your Safari Investment</div>
        <div class="section-line"></div>
    </div>

    <div class="pricing-summary">
        <div class="pricing-card">
            <strong><?php echo e($template->duration_days); ?> Days</strong>
            <span>Tour Length</span>
        </div>
        <div class="pricing-card">
            <strong><?php echo e($settings['guest_count'] ?? '2'); ?></strong>
            <span>Travellers</span>
        </div>
        <div class="pricing-card">
            <strong><?php echo e($template->days->first()?->date?->format('d M') ?? ''); ?></strong>
            <span>Start Tour</span>
        </div>
        <div class="pricing-card">
            <strong><?php echo e($template->days->last()?->date?->format('d M') ?? ''); ?></strong>
            <span>End Tour</span>
        </div>
    </div>

    <?php if($template->pricing->count()): ?>
    <table class="pricing-table">
        <thead>
            <tr><th>Description</th><th>Qty</th><th>Per Person</th><th>Total</th></tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $template->pricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr><td><?php echo e($p->notes ?? 'Safari Package'); ?></td><td>1</td><td><?php echo e($p->currency); ?> <?php echo e(number_format($p->price_per_person ?? 0, 2)); ?></td><td><?php echo e($p->currency); ?> <?php echo e(number_format($p->total_cost ?? 0, 2)); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr class="total-row"><td colspan="3"><strong>Grand Total</strong></td><td><strong><?php echo e($template->pricing->first()?->currency ?? 'USD'); ?> <?php echo e(number_format($template->pricing->sum('total_cost'), 2)); ?></strong></td></tr>
        </tbody>
    </table>
    <?php endif; ?>

    <div style="margin-top:10mm">
        <h4 style="font-family:'Montserrat',sans-serif;font-size:8px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-bottom:8px">Included</h4>
        <ul class="inclusion-list">
            <?php if($template->includes): ?>
                <?php $__currentLoopData = explode("\n", $template->includes); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($inc); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <li>All accommodation as specified</li>
                <li>Professional guide & safari vehicle</li>
                <li>Park & conservation fees</li>
                <li>Meals as per itinerary</li>
                <li>Airport transfers</li>
                <li>Bottled water</li>
            <?php endif; ?>
        </ul>
    </div>

    <div style="margin-top:8mm">
        <h4 style="font-family:'Montserrat',sans-serif;font-size:8px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-bottom:8px">Excluded</h4>
        <ul class="inclusion-list exclusion-list">
            <?php if($template->excludes): ?>
                <?php $__currentLoopData = explode("\n", $template->excludes); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($exc); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <li>International flights</li>
                <li>Travel insurance</li>
                <li>Visa fees</li>
                <li>Personal expenses</li>
                <li>Tips & gratuities</li>
            <?php endif; ?>
        </ul>
    </div>
</div>


<div class="page acceptance-section">
    <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
    <div style="max-width:500px;margin:0 auto">
        <div class="acceptance-title">Ready to Confirm Your Safari?</div>
        <div class="acceptance-text">Accept this proposal to secure your booking, or request changes if you'd like to tailor your journey further.</div>
        <div class="acceptance-buttons">
            <?php if($pdf ?? false): ?>
            <span class="btn-gold">Accept Proposal</span>
            <span class="btn-outline">Request Changes</span>
            <?php else: ?>
            <button class="btn-gold" onclick="document.getElementById('acceptForm').classList.toggle('hidden')">Accept Proposal</button>
            <button class="btn-outline" onclick="document.getElementById('changeForm').classList.toggle('hidden')">Request Changes</button>
            <?php endif; ?>
        </div>

        <?php if (! ($pdf ?? false)): ?>
        <div id="acceptForm" class="hidden" style="margin-top:20px;text-align:left;background:var(--panel-bg);border:1px solid var(--border-gold);padding:16px;border-radius:4px">
            <form id="acceptProposalForm">
                <?php echo csrf_field(); ?>
                <input type="text" name="customer_name" placeholder="Your Full Name" required style="width:100%;padding:10px;margin-bottom:8px;background:#1a1b18;border:1px solid var(--border-gold);color:var(--text-primary);font-size:10px;border-radius:3px">
                <input type="email" name="customer_email" placeholder="Your Email" required style="width:100%;padding:10px;margin-bottom:8px;background:#1a1b18;border:1px solid var(--border-gold);color:var(--text-primary);font-size:10px;border-radius:3px">
                <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text-secondary);margin-bottom:12px">
                    <input type="checkbox" name="accept_terms" required> I accept the booking terms and conditions
                </label>
                <button type="submit" class="btn-gold" style="width:100%">Confirm Acceptance</button>
            </form>
        </div>

        <div id="changeForm" class="hidden" style="margin-top:20px;text-align:left;background:var(--panel-bg);border:1px solid var(--border-gold);padding:16px;border-radius:4px">
            <form id="changeRequestForm">
                <?php echo csrf_field(); ?>
                <input type="text" name="customer_name" placeholder="Your Full Name" required style="width:100%;padding:10px;margin-bottom:8px;background:#1a1b18;border:1px solid var(--border-gold);color:var(--text-primary);font-size:10px;border-radius:3px">
                <input type="email" name="customer_email" placeholder="Your Email" required style="width:100%;padding:10px;margin-bottom:8px;background:#1a1b18;border:1px solid var(--border-gold);color:var(--text-primary);font-size:10px;border-radius:3px">
                <textarea name="message" placeholder="Your message" required style="width:100%;padding:10px;margin-bottom:8px;background:#1a1b18;border:1px solid var(--border-gold);color:var(--text-primary);font-size:10px;border-radius:3px;min-height:80px"></textarea>
                <textarea name="requested_changes" placeholder="Requested changes (optional)" style="width:100%;padding:10px;margin-bottom:8px;background:#1a1b18;border:1px solid var(--border-gold);color:var(--text-primary);font-size:10px;border-radius:3px;min-height:60px"></textarea>
                <button type="submit" class="btn-outline" style="width:100%">Submit Request</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>


<div class="page company-page">
    <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
    <img src="<?php echo e($agency->logo ?? ''); ?>" alt="" class="company-logo" onerror="this.style.display='none'">
    <div class="company-name"><?php echo e($agency->name ?? 'Shishi Footsteps'); ?></div>
    <div class="company-tagline"><?php echo e($agency->tagline ?? 'Luxury African Safari Experiences'); ?></div>

    <div class="company-stats">
        <div class="company-stat"><strong><?php echo e($agency->years_experience ?? 10); ?></strong><span>Years Experience</span></div>
        <div class="company-stat"><strong><?php echo e($agency->safaris_planned ?? 500); ?>+</strong><span>Safaris Planned</span></div>
        <div class="company-stat"><strong><?php echo e($agency->destinations_covered ?? 8); ?></strong><span>Destinations</span></div>
    </div>

    <div class="testimonial" style="text-align:left;margin-top:16px">
        <p>"An absolutely unforgettable experience. Every detail was perfect from start to finish."</p>
        <strong>– Sarah & Michael K.</strong>
    </div>
    <div class="testimonial" style="text-align:left;margin-top:8px">
        <p>"The most incredible safari. Our guide was knowledgeable, the lodges were stunning, and the wildlife was beyond our wildest dreams."</p>
        <strong>– David R.</strong>
    </div>

    <div class="contact-info">
        <p>Call or WhatsApp: +254 725 346 022</p>
        <p>General Inquiries: info@shishifootsteps.com</p>
        <p>Bookings: bookings@shishifootsteps.com</p>
        <p>Office: Nairobi, Kenya</p>
        <p style="margin-top:8px;font-size:8px;color:var(--text-secondary)">&copy; <?php echo e(date('Y')); ?> <?php echo e($agency->name ?? 'Shishi Footsteps'); ?>. All rights reserved.</p>
    </div>
</div>

<?php if (! ($pdf ?? false)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var acceptForm = document.getElementById('acceptProposalForm');
    if (acceptForm) {
        acceptForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Processing...';
            var formData = new FormData(this);
            fetch(window.location.pathname + '/accept', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) { alert(d.message); document.getElementById('acceptForm').innerHTML = '<p style="color:var(--light-gold);font-size:14px">' + d.message + '</p>'; }
                else { alert(d.message || 'Error'); btn.disabled = false; btn.textContent = 'Confirm Acceptance'; }
            })
            .catch(function() { alert('An error occurred. Please try again.'); btn.disabled = false; btn.textContent = 'Confirm Acceptance'; });
        });
    }

    var changeForm = document.getElementById('changeRequestForm');
    if (changeForm) {
        changeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Submitting...';
            var formData = new FormData(this);
            fetch(window.location.pathname + '/request-changes', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) { alert(d.message); document.getElementById('changeForm').innerHTML = '<p style="color:var(--light-gold);font-size:14px">' + d.message + '</p>'; }
                else { alert(d.message || 'Error'); btn.disabled = false; btn.textContent = 'Submit Request'; }
            })
            .catch(function() { alert('An error occurred. Please try again.'); btn.disabled = false; btn.textContent = 'Submit Request'; });
        });
    }
});
</script>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\itinerary-templates\luxury-dark\proposal.blade.php ENDPATH**/ ?>