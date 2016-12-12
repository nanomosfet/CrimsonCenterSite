<?php
include_once 'login.php';
createTable('news','id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    headline VARCHAR(256),
    news VARCHAR(4096),
    date VARCHAR(256),
    INDEX(headline(20)),
    INDEX(news(50))');

createTable('events','id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    headline VARCHAR(256), event VARCHAR(4096),
    place VARCHAR(256),
    date VARCHAR(256),
    INDEX(headline(20)),
    INDEX(event(50))');

createTable('admin','user VARCHAR(100),
    pass VARCHAR(100),
    INDEX(user(100)),
    INDEX(pass(100))');

createTable('staff','id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    staffname VARCHAR(256),
    stafftext VARCHAR(4096),
    stafftitle VARCHAR(256),
    staffemail VARCHAR(4096),
    INDEX(staffname(10)),
    INDEX(stafftext(10))');

createTable('homepage',
            'mStatement VARCHAR(2000),
            aboutus VARCHAR(4096),
            INDEX(mStatement(10)),
            INDEX(aboutus(10))');

createTable('links',
            'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                url VARCHAR(150),
            urlname VARCHAR(100),
            linkinfo VARCHAR(2056),
            INDEX(url(10)),
            INDEX(linkinfo(10)),
            INDEX(urlname(10))');
createTable('links',
            'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                url VARCHAR(150),
            urlname VARCHAR(100),
            linkinfo VARCHAR(2056),
            INDEX(url(10)),
            INDEX(linkinfo(10)),
            INDEX(urlname(10))');

createTable('contact',
    'email VARCHAR(100),
        phone varchar(20),
        fax VARCHAR(20),
        INDEX(phone(10)),
        INDEX(fax(10)),
    INDEX(email(100))');
createTable('location',
    'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR( 60 ) NOT NULL,
    address VARCHAR( 80 ) NOT NULL,
    lat FLOAT( 10, 6 ) NOT NULL ,
    lng FLOAT( 10, 6 ) NOT NULL,
    type VARCHAR( 30 ) NOT NULL');
createTable('service_title',
            'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            service_title VARCHAR(100),
            INDEX(service_title(10))');
createTable('service_list',
            'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title_id VARCHAR(100),
            service VARCHAR(100),
            INDEX(title_id(10)),
            INDEX(service(10))');

