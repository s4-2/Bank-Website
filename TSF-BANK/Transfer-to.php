<?php
include 'conn.php';

if (isset($_POST['submit'])) {
    $from = $_GET['id'];
    $to = $_POST['to'];
    $amount = $_POST['amount'];

    $sql = "SELECT * from user where id=$from";
    $query = mysqli_query($conn, $sql);
    $sql1 = mysqli_fetch_array($query); // returns array or output of user from which the amount is to be transferred.

    $sql = "SELECT * from user where id=$to";
    $query = mysqli_query($conn, $sql);
    $sql2 = mysqli_fetch_array($query);



    // constraint to check input of negative value by user
    if (($amount) < 0) {
        echo '<script type="text/javascript">';
        echo ' alert("Oops! Negative values cannot be transferred")';  // showing an alert box.
        echo '</script>';
    }



    // constraint to check insufficient balance.
    else if ($amount > $sql1['Balance']) {

        echo '<script type="text/javascript">';
        echo ' alert("Bad Luck! Insufficient Balance")';  // showing an alert box.
        echo '</script>';
    }



    // constraint to check zero values
    else if ($amount == 0) {

        echo "<script type='text/javascript'>";
        echo "alert('Oops! Zero value cannot be transferred')";
        echo "</script>";
    } else {

        // deducting amount from sender's account
        $newbalance = $sql1['Balance'] - $amount;
        $sql = "UPDATE user set Balance=$newbalance where id=$from";
        mysqli_query($conn, $sql);


        // adding amount to reciever's account
        $newbalance = $sql2['Balance'] + $amount;
        $sql = "UPDATE user set Balance=$newbalance where id=$to";
        mysqli_query($conn, $sql);

        $sender = $sql1['Name'];
        $receiver = $sql2['Name'];
        $sql = "INSERT INTO transaction(`sender`, `receiver`, `balance`) VALUES ('$sender','$receiver','$amount')";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            echo "<script> alert('Transaction Successful');
                                     window.location='transactionhistory.php';
                           </script>";
        }

        $newbalance = 0;
        $amount = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">

    <style type="text/css">
        button {
            border: none;
            background: lightblue;
        }

        button:hover {
            background-color: blue;
            transform: scale(1.1);
            color: white;
        }
    </style>
</head>

<body style="background-color : lightgreen ;">

    <?php
    include 'navbar.php';
    ?>

    <div class="container-fluid">
        <h2 class="text-center pt-4" style="color : black;">Transaction</h2>
        <?php
        include 'conn.php';
        $sid = $_GET['id'];
        $sql = "SELECT * FROM  user where id=$sid";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "Error : " . $sql . "<br>" . mysqli_error($conn);
        }
        $rows = mysqli_fetch_assoc($result);
        ?>
        <form method="post" name="tcredit" class="tabletext"><br>
            <div>
                <table class="table table-striped table-condensed table-bordered">
                    <tr style="color : black;">
                        <th class="text-center">Id</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Balance</th>
                    </tr>
                    <tr style="color : black;">
                        <td class="py-2"><?php echo $rows['id'] ?></td>
                        <td class="py-2"><?php echo $rows['Name'] ?></td>
                        <td class="py-2"><?php echo $rows['E-mail'] ?></td>
                        <td class="py-2"><?php echo $rows['Balance'] ?></td>
                    </tr>
                </table>
            </div>
            <br><br><br>
            <label style="color : red;"><b>Transfer To:</b></label>
            <select name="to" class="form-control" required>
                <option value="" disabled selected>Choose</option>
                <?php
                include 'conn.php';
                $sid = $_GET['id'];
                $sql = "SELECT * FROM user where id!=$sid";
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    echo "Error " . $sql . "<br>" . mysqli_error($conn);
                }
                while ($rows = mysqli_fetch_assoc($result)) {
                ?>
                    <option class="table" value="<?php echo $rows['id']; ?>">

                        <?php echo $rows['Name']; ?> (Balance:
                        <?php echo $rows['Balance']; ?> )

                    </option>
                <?php
                }
                ?>
                <div>
            </select>
            <br>
            <br>
            <label style="color : red;"><b>Amount:</b></label>
            <input type="number" class="form-control" name="amount" required>
            <br><br>
            <div class="text-center">
                <button class="btn mt-3" name="submit" type="submit" id="myBtn">Transfer</button>
            </div>
        </form>
    </div>


    <?php
    include('footer.php');
    ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

</body>

</html>