<?php
// Tell server that you will be tracking session variables
session_start();
?>
<!DOCTYPE html>
<html>
<!-- inventory.php - keep track of inventory
     Peter Johnson - peter.johnson@southcentral.edu
     Written:  08-09-11
     Revised:
     
     inventory8.php - add a record in an array based on input from a form
     (1) Collect data from a second form:  frmAdd
         Add accessibility features: <fieldset> <legend> and id=" " 
     (2) Append that information to the array using array_push( ) (slower) or $array = (fast)
     (3) Save the array as a serialized session variable
     (4) Sort by column http://www.prodevtips.com/2008/01/06/sorting-2d-arrays-in-php-anectodes-and-reflections/
     (5) Redisplay the array.
     
$invenArray - Hard-code in 2D array of inventory - partNo, descr, price, qty  (partNo is index field)
              Later this will be populated from a database table.
displayInventory( ) - Display the inventory
addItem( )          - Create a form that will add a new item to inventory
deleteItem( )       - Create a form that will delete an item from the inventory

-->
<head>
    <title>My Inventory</title>
    <style type="text/css">
        fieldset#fieldsetAdd {
            border: #008000 solid 1px; /* forest green */
            background-color: white;
        }

            fieldset#fieldsetAdd legend {
                color: #008000; /* forest green */
                background-color: white;
            }

        fieldset#fieldsetDelete {
            border: #800000 solid 1px; /* dark red */
            background-color: white;
        }

            fieldset#fieldsetDelete legend {
                color: #800000;
                background-color: white;
            }

        table {
            background-color: white;
        }
    </style>

    <script type="text/javascript">
  //<![CDATA[

  //]]>
    </script>
</head>
<body>

    <?php

    // The filename of the currently executing script to be used
    // as the action=" " attribute of the form element.
    $self = $_SERVER['PHP_SELF'];

    // check to see if this is the first time viewing the page
    // The hidFunctionCode will not exist if this is the first time
    if (array_key_exists('hidFunctionCode',$_POST))
    {
        // Look at the hidden submitFlag variable to determine what to do
        $submitFlag = $_POST['hidFunctionCode'];

        // Get the array that was stored as a session variable
        $invenArray = unserialize(urldecode($_SESSION['serializedArray']));

        switch($submitFlag)
        {
            case 01: addRecord( ); break;
            case 99: deleteRecord( ); break;
            default: displayInventory($invenArray );
        } // end of switch($submitFlag)
    }
    else // first time coming to this page
    {
        echo("<h1>Welcome to the Inventory Page</h1>");
        // First time visitor? Create the inventory array
        $invenArray = array(
            array(111,"Rose",1.95,100),
            array(222,"Dandelion Tree",2.95,200),
            array(333,"Crabgrass Peach",3.95,300)
            );

        // Save this array as a serialized session variable
        $_SESSION['serializedArray'] = urlencode(serialize($invenArray));
    }

    /***************************************
    Functions are in alphabetical order.
     **************************************/
    function addRecord( )
    {
        global $invenArray;
        // add the new information into the array
        $invenArray[ ] = array($_POST['txtPartNo'], $_POST['txtDescr'], $_POST['txtPrice'], $_POST['txtQty']);
        sort($invenArray);

        // Save the updated array in its session variable
        $_SESSION['serializedArray'] = urlencode(serialize($invenArray));
    } // end of addRecord( )


    function deleteRecord( )
    {
        global $invenArray;
        global $deleteMe;
        // Get the index from lstItem that was selected
        $deleteMe = $_POST['lstItem'];
        // remove the selected index from the array
        unset($invenArray[$deleteMe]);
        // Save the updated array in its session variable
        $_SESSION['serializedArray'] = urlencode(serialize($invenArray));
    } // end of function deleteRecord( )


    function displayInventory(array $thisArray)
    {
        echo("<table border='0' summary='Inventory Items'>");
        // add in headers
        echo("<tr><th>Part No</th><th>Description</th><th>Price</th><th>Qty</th></tr>");

        // walk through each record or row
        foreach($thisArray as $displayRecord)
        {
            echo "<tr>";
            // for each record display the details in columns
            foreach ($displayRecord as $value)
            {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        // close off the table
        echo("</table><br />");
    }  // end of function displayInventory( )

    ?>


    <img src="graphic/branch.jpg" alt="branch with green leaves" align="left" />
    <h1>Plants You-nique</h1>
    <p>
        <strong>Current inventory:</strong>
        <br />
        <?php displayInventory($invenArray ); ?>
    </p>


    <!-- ************************************************
        ADD FORM
        This page will call itself when user clicks Add button
        The hidFunctionCode will tell the server what function to run
        ************************************************ -->
    <form action="<?php $self ?>"
        method="post"
        name="frmAdd">
        <!-- <fieldset> <legend> and id=" " are included for increased accessibility -->
        <fieldset id="fieldsetAdd">
            <legend>Add An Item</legend>
            <label for="txtPartNo">Part Number</label>:
            <input type="text" name="txtPartNo" id="txtPartNo" value="999" size="5" />&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="txtDescr">Description</label>:
            <input type="text" name="txtDescr" id="txtDescr" value="Test Descr" />
            <br />
            <br />
            <label for="txtPrice">Price</label>: $US
            <input type="text" name="txtPrice" id="txtPrice" value="123.45" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label for="txtQty">Number in Stock:</label>
            <input type="text" name="txtQty" id="txtQty" value="123" size="5" />

            <!-- This field is used to determine if the page has been viewed already
           It also designates what function to run each time the page is rebuilt on the server. -->
            <input type='hidden' name='hidFunctionCode' value='01' />
            <input name="btnSubmit" type="submit" value="Add This Item" />
        </fieldset>

    </form><!-- end of frmAdd -->
    <br />
    <br />

    <!-- ************************************************
        DELETE FORM
        This page will call itself when user clicks Delete button
        ************************************************ -->
    <form action="<?php $self ?>"
        method="post"
        name="frmDelete">
        <!-- <fieldset> <legend> and id=" " are included for increased accessibility -->
        <fieldset id="fieldsetDelete">
            <legend>Delete an Item</legend>
            <label for="lstItem">Select an item to delete: </label>
            <select name="lstItem" id="lstItem" size="1">
                <?php
                // Populate the list box using the data from the array
                foreach($invenArray as $index => $lstRecord)
                {
                    // make the value the index and the text displayed the description from the array
                    echo "<option value='" . $index . "'>" . $lstRecord[1] ."</option>\n";
                }
                ?>
            </select>
            <!-- This field is used to determine if the page has been viewed already.
           It also designates which function to run each time the page is rebuilt. -->
            <input type='hidden' name='hidFunctionCode' value='99' />
            &nbsp;&nbsp;&nbsp;
            <input name="btnSubmit" type="submit" value="Delete" />
        </fieldset>
    </form><!-- end of frmDelete -->

</body>
</html>
