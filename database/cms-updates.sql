-- CMS Content Blocks — new sections for cPanel / phpMyAdmin
-- Run AFTER migrations have created the cms_content_blocks table

-- HOME: add YouTube video ID
INSERT IGNORE INTO `cms_content_blocks` (`page`,`key`,`type`,`value`,`created_at`,`updated_at`)
VALUES ('home','youtube_id','text','1CYVG70ZbyQ',NOW(),NOW());

-- ABOUT: add CTA fields
INSERT IGNORE INTO `cms_content_blocks` (`page`,`key`,`type`,`value`,`created_at`,`updated_at`)
VALUES
('about','cta_title','text','Let Us Design Your Journey',NOW(),NOW()),
('about','cta_text','textarea','Tell us what you are dreaming of, and we will shape a safari with the right destinations, pace, guides and lodges - built from scratch around you.',NOW(),NOW()),
('about','cta_image','image','https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1800&q=82&fm=webp',NOW(),NOW());

-- FAQs: entire section
INSERT IGNORE INTO `cms_content_blocks` (`page`,`key`,`type`,`value`,`created_at`,`updated_at`)
VALUES
('faqs','hero_title','text','Frequently Asked Questions',NOW(),NOW()),
('faqs','hero_subtitle','textarea','Everything you need to know about planning your African golf safari and travel experiences.',NOW(),NOW()),
('faqs','hero_image','image','',NOW(),NOW()),
('faqs','editorial_title','text','Your questions, answered',NOW(),NOW()),
('faqs','editorial_text','textarea','Whether you are planning a golf tour, combining a safari with your rounds, or arranging a complete African holiday, we are here to make the process effortless. Below you will find answers to the most common questions our travellers ask.',NOW(),NOW());

-- GOLF: all fields
INSERT IGNORE INTO `cms_content_blocks` (`page`,`key`,`type`,`value`,`created_at`,`updated_at`)
VALUES
('golf','hero_label','text','African Golf Holidays',NOW(),NOW()),
('golf','hero_title','text','Beyond the Fairways',NOW(),NOW()),
('golf','hero_subtitle','textarea','Championship golf, carefully timed tee sheets and smooth travel between Africas most rewarding courses.',NOW(),NOW()),
('golf','hero_image','image','',NOW(),NOW()),
('golf','youtube_id','text','iG5nlWiP9Ro',NOW(),NOW()),
('golf','intro_label','text','Tee Off With Shishi Footsteps',NOW(),NOW()),
('golf','intro_title','text','A golf safari designed around your game',NOW(),NOW()),
('golf','intro_text','textarea','Shishi Footsteps designs luxury golf safaris across Africa. We combine access to premier golf courses with unforgettable safari adventures, relaxing coastal escapes, premium accommodation and meaningful cultural experiences. Every itinerary is thoughtfully designed around the travellers interests, travel style and preferred pace.',NOW(),NOW()),
('golf','services_label','text','The Shishi Difference',NOW(),NOW()),
('golf','services_title','text','Everything a travelling golfer needs',NOW(),NOW()),
('golf','packages_label','text','Golf Itineraries',NOW(),NOW()),
('golf','packages_title','text','Choose a fairway, then make it yours',NOW(),NOW()),
('golf','courses_label','text','Premier Courses',NOW(),NOW()),
('golf','courses_title','text','Africas standout fairways',NOW(),NOW()),
('golf','complete_label','text','Complete Golf Service',NOW(),NOW()),
('golf','complete_title','text','Everything around your round, handled',NOW(),NOW()),
('golf','cta_label','text','Your Golf Safari',NOW(),NOW()),
('golf','cta_title','text','Where Passion for Golf Meets the Spirit of Adventure',NOW(),NOW()),
('golf','cta_text','textarea','Tell us your preferred courses, travel dates and handicap. Our specialists will create a personalised African golf safari around you.',NOW(),NOW()),
('golf','cta_image','image','',NOW(),NOW());
