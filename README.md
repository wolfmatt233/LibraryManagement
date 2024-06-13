# Laravel Library Management System

Matthew Wolf

## Description

This is a system that allows users to borrow books and return them before a certain due date. It will allow users to filter and search through a custom list of books in the database. Users can also add books to a personal wishlist. Admin features like adding, editing, and deleting books along with updating any loan will be included. Account creation and login is handled by laravel breeze along with a lot of css layout using Tailwind.

## To Do

- Display and paginate books.
- Display individual books.
    - Create loans as a user (borrow).
    - Add books to wishlist
- Search and filter books.
- Display and paginate loans.
    - Remove loan as a user (return).
- View past loans.
- Users place holds on backordered books.

MySQL Database:

-   Books
    -   Title
    -   Author
    -   Genre
    -   Description
    -   ISBN
    -   Publisher
    -   Image
    -   Number of copies available
-   Loans
    -   Book id
    -   User id
    -   Due date
- Holds
    - Book id
    - User id
    - Hold date (place in line)
-   Users
    - Handled by Laravel Breeze
-   Wishlist
    -   Book id
    -   User id
