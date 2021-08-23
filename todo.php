<?php

//Set up database conenction using credentials saved in uncommitted file

require 'secrets.php' ;

$con = sqlsrv_connect( DB_SRV ,
    [ 'Database' => 'example_db' ,
        'UID' => DB_UID ,
        'PWD' => DB_PWD ]
    ); 

//Print error mesage if the database doesn't connect

if ($con === false ){
    echo 'Failed to connect to db: ' . sqlsrv_errors()[ 0 ][ 'message' ];
    exit ();
} 


$items_to_show = 'Not started';

//|Check if the request method was post

if ($_SERVER[ 'REQUEST_METHOD' ] === 'POST' ){
    
    //if it was a request to hid/show tasks, execute this code:
    if ( isset ($_POST[ 'items_to_show' ])){
        
        //Toggle to the opposite state:
        if($items_to_show === "Not started"){
            $items_to_show = $_POST[ 'items_to_show' ];
        } elseif ($items_to_show === "All"){
            $items_to_show = $_POST[ 'items_to_show' ];
        }
    }
    
    //If it was a task removal click, execute this code:
    elseif ( isset ($_POST[ 'id' ])){
    
        //Retrieve the hidden id and store it in a variable
        $id = $_POST[ 'id' ];
        
        //Prepare a statement but don't execute it yet. Save to a variable.
        //This two step approach is to prevent unauthorised changes to the database
        $stmt = sqlsrv_prepare($con,
            'UPDATE todos SET status = \'Completed\' WHERE id=?' ,
            [$id]
            );
        
        //Use the check error helper function to print an error if this went wrong
        check_err($stmt);
        
        //Now execute the statement
        $res = sqlsrv_execute($stmt);
        
        //Use the check error helper function to print an error if this went wrong
        check_err($res);
        
        //In the case of success, print this:
        //echo '<p>Successfully deleted to-do item</p>' ;
    } 
    
    //But if it was a new task added, execute this code:
    else {
    
        //If so, retrieve the input and store it in a variable
        $new_title = $_POST[ 'title' ];
        
        //Prepare a statement but don't execute it yet. Save to a variable.
        //This two step approach is to prevent unauthorised changes to the database
        $stmt = sqlsrv_prepare($con,
            'INSERT INTO todos(title, status) VALUES (?, ?)' ,
            [$new_title, "Not started"]);
        
        //Use the check error helper function to print an error if this went wrong
        check_err($stmt);
        
        //Now execute the statement
        $res = sqlsrv_execute($stmt);
        
        //Use the check error helper function to print an error if this went wrong
        check_err($res);
        
        //In the case of success, print this:
        //echo '<p>To-do item successfully inserted</p>' ;
    }
}

function check_err ($var){
    if ($var === false ){
        echo 'DB failure: ' . sqlsrv_errors()[ 0 ][ 'message' ];
        exit ();
    }
}

?>

<!-- Table listing the existing todo items-->

<h2> To-do list items </h2>
<?php 

//Depending ont he toggle state of which tasks to show, serve one of these buttons:
if ($items_to_show === 'Not started') {
echo '<form method="post" action="todo.php">
            <input type="hidden" name="items_to_show" value="All">
            <button type="submit">Show completed</button>
            </form>';
} elseif ($items_to_show === 'All') {
    echo '<form method="post" action="todo.php">
            <input type="hidden" name="items_to_show" value="Not started">
            <button type="submit">Hide completed</button>
            </form>';
}
?>
<table>
    <tbody>
        <tr>
        	<th> Item </th>
        	<th> Added on </th>
        	<th> Status </th>
        	<th> Complete </th>
        </tr>
        
        <!-- Retrieving data from the database, which will come back in the 
        form of a variable-->
        <?php
        
        //If the toggle state is incomplete only, select only those from the database:
        if ($items_to_show === 'Not started') {
            $stmt = sqlsrv_query($con, 'SELECT id, title, created, status FROM 
                todos WHERE status = \'Not started\'' );
            check_err($stmt);
            //If the toggle state is all, select all tasks from the database:
        } elseif ($items_to_show === 'All') {
            $stmt = sqlsrv_query($con, 'SELECT id, title, created, status FROM 
                todos' );
            check_err($stmt);
        }
        
        // Now iterate over the rows in the query result, and convert into HTML
        while ($row = sqlsrv_fetch_array($stmt)){
            $title = $row[ 'title' ];
            $created = $row[ 'created' ]->format( 'j F' );
            $id = $row[ 'id' ];
            $status = $row[ 'status' ];
           
            echo '<tr>' ;
            //depending on the status of the task, show as strikethrough or not
            
            if ($status === 'Completed'){
                echo '<td><del>' . $title . '</del></td>' ;
                echo '<td><del>' . $created . '</del></td>' ;
                echo '<td><del>' . $status . '</del></td>' ;
            } elseif ($status === 'Not started'){
                echo '<td>' . $title . '</td>' ;
                echo '<td>' . $created . '</td>' ;
                echo '<td>' . $status . '</td>' ;
            }
            
            //Button with hidden input in each row to mark the task as done:
            echo '<td>
                        <form method="post" action="todo.php">
                            <input type="hidden" name="id" value="' .$id. '">
                            <button type="submit">Done</button>
                        </form>
                      </td>' ;
            echo '</tr>' ;
            
        }
        
        //Close the connection with the database
        sqlsrv_close($con);
        
        ?>
    </tbody >
</table >

<!-- Button to submit a new task-->
<br>
<form method= "post" action= "todo.php">
<input type= "text" name= "title" placeholder= "To-do item">
<button type= "submit">Submit</button>
<br>

</form >