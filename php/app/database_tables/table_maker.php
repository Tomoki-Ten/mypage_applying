<?php
    try{
        $dsn = 'mysql:host=db;dbname=php_mypage';
        $user = 'phpmypage';
        $password = 'phpmypage';
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    // // テーブル managers 作る用 SQL
    //     $dbh->exec("CREATE TABLE managers(
    //                 id INT AUTO_INCREMENT,
    //                 name VARCHAR(40),
    //                 password VARCHAR(255),
    //                 email VARCHAR(50),
    //                 code_to_verify varchar(100),
    //                 verification int(1) DEFAULT 0,
    //                 created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    //                 updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    //                                     ON UPDATE CURRENT_TIMESTAMP,
    //                 PRIMARY KEY(id),
    //                 UNIQUE KEY(email))"
    //                 );

    // // テーブル posts 作る用 SQL
        // $dbh->exec ("CREATE TABLE posts(
        //             id INT AUTO_INCREMENT,
        //             manager_id INT,
        //             title VARCHAR(50),
        //             post VARCHAR(800),
        //             image_name VARCHAR(700),
        //             created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        //             updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        //                                 ON UPDATE CURRENT_TIMESTAMP,
        //             PRIMARY KEY(id),
        //             FOREIGN KEY(manager_id)
        //             REFERENCES managers(id)
        //             ON DELETE SET NULL
        //             ON UPDATE CASCADE)"
        //             );

    // テーブル comments 作る用 SQL
        // $dbh->exec ("CREATE TABLE comments(
        //             id INT AUTO_INCREMENT  PRIMARY KEY,
        //             post_id INT,
        //             comment VARCHAR(800),
        //             created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        //             updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        //                                 ON UPDATE CURRENT_TIMESTAMP,
        //             FOREIGN KEY(post_id)
        //             REFERENCES posts(id)
        //             ON DELETE SET NULL
        //             ON UPDATE CASCADE)"
        //             );
        
        $dbh = null;
        print 'Conection is available.';
        print 'the Table you wanted to create is successfully created !!';
    }catch(PDOException $e){
        print 'ERROR: ' . $e->getMessage();
    }
