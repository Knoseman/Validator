<html>
<head>
  <title>JSON Validator</title>
  <link id="favicon" href="https://files.readme.io/4f00763-small-302f957-small-32x32.png" rel="shortcut icon" type="image/png">
  <style>
  .menu {
    float: left;
  }
  .menu button {
    display: inline-block;
    font-size: 14px;
    color: black;
    text-decoration: none;
    background-color: #d2d2d2;
    border-radius: 4px;
    padding: 10px;
    margin: 5px 5px;
    padding: 5px;
    outline:none;
  }
  .menu button:hover {
    background-color: #5fb705;
    color: white;
  }
  .menu button:focus {
    background-color: #5fb705;
    color: white;
  }

input[type=checkbox] {
  /* Double-sized Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  transform: scale(2);
  padding: 10px;
}
.checkboxtext {
  /* Checkbox text */
  font-size: 110%;
  display: inline;
}
  </style>
</head>
<body>
  <h2>JSON validator</h2>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="APIselector">
    Paste json data into the textbox to validate it. Supported methods: Deposit, Withdraw, Refund, SelectAccount, RegisterAccount, AccountPayout and Charge. 
    <br/>

    <textarea name="Data" rows="30" cols="80" placeholder="<<< PASTE APICALLS.DATA HERE >>>"></textarea>
    <br/>
  <div class="menu">
      <button onClick="document.getElementById('APIselector').submit();" value="Deposit" name="APIselector">Deposit</input> 
      <button onClick="document.getElementById('APIselector').submit();" value="Withdraw" name="APIselector">Withdraw</input>
      <button onClick="document.getElementById('APIselector').submit();" value="Refund" name="APIselector">Refund</input>
      <button onClick="document.getElementById('APIselector').submit();" value="SelectAccount" name="APIselector">SelectAccount</input>
      <button onClick="document.getElementById('APIselector').submit();" value="RegisterAccount" name="APIselector">RegisterAccount</input>
      <button onClick="document.getElementById('APIselector').submit();" value="AccountPayout" name="APIselector">AccountPayout</input>
      <button onClick="document.getElementById('APIselector').submit();" value="Charge" name="APIselector">Charge</input>
 <!--     <button onClick="document.getElementById('APIselector').submit();" value="Deposit" name="APIselector">Deposit EMO</input>  //-->
  </div>
  </form>
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $method = $_POST["APIselector"];
    $jsondata = '{"method": "' . $method . '","params": {"Signature": "f4ThjuMqbsdG6u ... S16VbzD4h==","UUID": "258a2184-2842-b485-25ca-293525152425","Data": ' . $_POST["Data"] . '},"version": "1.1"}';
    $jsondata = trim($jsondata);
    $jsondata =  str_replace("|", "", $jsondata);
    require(__DIR__ . '/vendor/autoload.php');
    $data = json_decode($jsondata);

  // Validate
    $validator = new JsonSchema\Validator;
    $dataArr = json_decode($jsondata, true);
    echo "<br/><h4>API method : " . $method . "</h4>"; 
    echo '<code>' . htmlentities($jsondata) . '</code><br/><br/>';

    switch ($method) {
      case "Deposit":
        if ($_POST["emo"] == "emo") {
            $validator->check($data, (object)['$ref' => 'file://' . realpath('deposit_emo_schema.json')]);
        } else {
            $validator->check($data, (object)['$ref' => 'file://' . realpath('deposit_schema.json')]);
        }
      break;
      case "Deposit_EMO":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('deposit_emo_schema.json')]);
      break;
      case "Withdraw":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('withdraw_schema.json')]);
      break;
      case "Refund":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('refund_schema.json')]);
      break;
      case "SelectAccount":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('selectaccount_schema.json')]);
      break;
      case "AccountPayout":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('accountpayout_schema.json')]);
      break;
      case "Charge":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('charge_schema.json')]);
      break;
      case "RegisterAccount":
        $validator->check($data, (object)['$ref' => 'file://' . realpath('registeraccount_schema.json')]);
      break;
    }
    if ($validator->isValid()) { 
      echo "<font size='5' color='green'>OK! Validation successful.</font>";
    } 
    else {
      echo "<font size='5' color='red'>ERROR - does not validate. Violations: </font><br/>";
      foreach ($validator->getErrors() as $error) {
          echo sprintf("[%s] %s\n", $error['property'], $error['message']);
          echo "<br/>";
      }
    }
  }
  ?>
</body>
</html>
