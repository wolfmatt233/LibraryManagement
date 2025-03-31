# Laravel Library Management System

Author: [Matthew Wolf](https://www.linkedin.com/in/matthew-wolf2)

[Youtube Demo](https://youtu.be/oib3EMAtNmk)

## Description

This is a system that allows users to borrow books and return them before a certain due date. It will allow users to filter and search through a custom list of books in the database. Users can also add books to a personal wishlist. Admin features like adding, editing, and deleting books along with updating any loan are included. If a book is out of stock, a hold can be placed that will automatically place a loan when the book becomes available again. Account creation and login is handled by laravel breeze along with a lot of css layout using Tailwind.

[Demo Video](https://youtu.be/oib3EMAtNmk?si=Ye6BvduQC0jz0vgL)

MySQL Database:

-   Books
    -   Title
    -   Author
    -   Genre
    -   Description
    -   ISBN
    -   Publisher
    -   Image reference (image stored in  "storage/app/public/images")
    -   Number of copies available
-   Loans
    -   Book id
    -   User id
    -   Borrow date
    -   Due date
    -   Status (returned or borrowed)
    -   Return date
-   Holds
    -   Book id
    -   User id
    -   Created at timestamp used for place in line
-   Users
    -   Handled by Laravel Breeze
    -   Admin column (bool)
-   Wishlist
    -   Book id
    -   User id
