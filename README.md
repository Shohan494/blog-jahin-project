# Blog Jahin Project

#### initial notes

- Database - it can be separate, like separate connection with your project code
    - sql/mysql, postgres, mongodb, sqlite (query language - query (read/get/search/insert/delete/update))

- when in server ( for our local settings it is (apache server) which we are using came with xampp/wampp) any php project runs, then the project root folder when opened - it will search for index.html or index.php

- mysqli_real_escape_string, sql injection

- Sir's suggestions to use - cookie, ajax, server, session, mvc

- basic info about admin - we have manually entered the row through query/dbms

####

- for later: 
    - when sign up, also insert for user_profile
    - alike profile update in settings, email field validation

    - using controller for routing( which link or page will go where, will call which page or function)
    - in comments table, there is user id and also author id - if needed will fix it
    - need to clear about foreign key, more than one is causing issues
    - multiple para post probably is not working

    - logout and logged in check, verify user role for operations
    - navvar logout fix

    - settings -> status of profile edit check
    - $sqlCheck = "SELECT COUNT(*) AS count FROM user_profile WHERE pro_id = $userId" (make some alternative approaches)

    - comment section has some more advanced features probably by requirements

    - [x] categories while creating posts
    - role based update/delete
    - for each file convert into raw php mysql

    - when deleted this message appears in url - message=Post+deleted+successfully
    - categories and tags functioning are kind of similar. later will discuss
    

#### Questions:

1. Redirecting/(Forcefully moving user) to another page : header location, also some other ways are there(gpt)
2. varchar - for small input, for larger input - text
3. htmlspecialchars/sanitize/trim
4. created_at DESC - basically this will work reverse way to fetch data, so latest data will show first in order