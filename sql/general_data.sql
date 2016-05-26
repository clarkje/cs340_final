-- Possible album status
INSERT INTO astatus (description) VALUES ("On Shelf");
INSERT INTO astatus (description) VALUES ("Checked Out");
INSERT INTO astatus (description) VALUES ("Reserved");
INSERT INTO astatus (description) VALUES ("Reference");
INSERT INTO astatus (description) VALUES ("Missing");

-- User types
INSERT INTO utype (description) VALUES ("Administrator");
INSERT INTO utype (description) VALUES ("Undergraduate");
INSERT INTO utype (description) VALUES ("Graduate");
INSERT INTO utype (description) VALUES ("Library Staff");

-- User status
INSERT INTO ustatus (description) VALUES ("Active");
INSERT INTO ustatus (description) VALUES ("Disabled");
INSERT INTO ustatus (description) VALUES ("Block_Fine");
