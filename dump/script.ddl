
CREATE TABLE bendras_fondas
(
	id int,
	bendra_fondo_suma double,
	PRIMARY KEY(id)
);

CREATE TABLE palukanu_dydis
(
	id int,
	dydis double,
	PRIMARY KEY(id)
);

CREATE TABLE paskolos
(
	id int AUTO_INCREMENT,
	vardas varchar (255),
	pavarde varchar (255),
	data date,
	palukanos double,
	suma double,
	grazinimo_terminas int,
	fk_palukanu_dydis int NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(fk_palukanu_dydis),
	CONSTRAINT palukanos FOREIGN KEY(fk_palukanu_dydis) REFERENCES palukanu_dydis (id)
);

CREATE TABLE grafikai
(
	id int AUTO_INCREMENT,
	vartotojo_id int,
	data date,
	suma double,
	fk_paskola int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT fkc_paskola FOREIGN KEY(fk_paskola) REFERENCES paskolos (id)
);

CREATE TABLE vartotojai
(
	id int AUTO_INCREMENT,
	slapyvardis varchar (255),
	slaptazodis varchar (255),
	lygis int,
	timestamp date,
	vardas varchar (255),
	pavarde varchar (255),
	gimimo_data date,
	adresas varchar (255),
	telefonas varchar (255),
	email varchar (255),
	fk_paskola int NULL,
	fk_palukanu_dydis int NULL,
	PRIMARY KEY(id),
	CONSTRAINT fkc_paskolapaskola FOREIGN KEY(fk_paskola) REFERENCES paskolos (id),
	CONSTRAINT fkc_palukanu_dydis FOREIGN KEY(fk_palukanu_dydis) REFERENCES palukanu_dydis (id)
);

CREATE TABLE fondai
(
	id int AUTO_INCREMENT,
	pinigu_suma double,
	fk_vartotojas int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT fkc_vartotojas FOREIGN KEY(fk_vartotojas) REFERENCES vartotojai (id)
);
