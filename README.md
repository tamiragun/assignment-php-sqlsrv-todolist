# To do list

This was an assignment for the PHP and SQL Server portion of my coding bootcamp. It is an online todo list, which allows the user to add tasks to their to do list, and tick them off as done. By default it only shows incomplete tasks, but the user can choose to also see completed tasks, which are styled differently.

**Stack used**: PHP - SQL Server - HTML

## The brief
These were the detailed instructions for the assignment:
- Create a to-do page that connects to a database.
- Create an HTML form to submit a to do item. 
    - Upon submission, add the item to the database.
- Create a table which lists the to do items. 
    - It should have a column to keep track of whether a task is complete. 
    - By default, only show completed tasks.
- Each item should have a button to mark the task as done. 
    - Once ticked, they should disappear from the default view.
- Add a button below the tasks labelled “Show completed” that will reload the page and display all tasks (completed and uncompleted). 
    - After this, the button should show “Hide completed” with appropriate functionality.
    - Completed tasks should appear crossed out in HTML.

## My contribution
All of the code I wrote can be found in: 'todo.php' in the root folder.

## How to install this project
1. Download Microsoft SQL Server if you don't already have it: https://www.microsoft.com/en-us/sql-server/sql-server-downloads
2. Clone this Github repository into a directory of your choice.
3. In Microsoft SQL, create a database and import the data contained in this file: 'example_db_export.xlsx'.
4. Open the todo.php file, and enter the database info for the database you just created. It should look something like this:
    ```
    $con = sqlsrv_connect( 'your server' ,
        [ 'Database' => 'your database name' ,
            'UID' => 'your user id' ,
            'PWD' => 'your password' ]
    ); 
    ```
5. Open a terminal in the folder of your project, and run the following to start a local webserver: `php -S localhost:8080`.
6. In your browser, navigate to your localhost to see the site in action: http://localhost:8080.

