USE restaurant;

INSERT INTO user (first_name, last_name, email, status, password) VALUES
('Matej', 'Malcic', 'matej.malcic3@gmail.com', 'Admin', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Stefan', 'Staffer', 'stefan@staff.com', 'Staff', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Regular', 'Guest', 'noLogin@user.com', 'Guest', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Reggie', 'Register', 'registered@user.com', 'Guest', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta');

INSERT INTO category (name) VALUES
('Breakfast'),
('Lunch'),
('Dinner');

INSERT INTO status (name) VALUES
('Preparing'),
('Ready to take'),
('Finished');

INSERT INTO product (name, image, description, category, price) VALUES
('Eggs & Bacon', 'eggsbacon.jpg', '2 eggs with fried bacon', 1, 4.00),
('Meatballs', 'meatballs.jpg', 'Saucy meatballs with mushrooms', 2, 9.00),
('Chicken Curry', 'chickencurry.jpg', 'Chicken curry rice', 3, 7.50);
