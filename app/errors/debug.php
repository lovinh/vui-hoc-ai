<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>

<body>
    <?php
    echo "<h1>" . $data["type"] . "</h1>";

    echo "<h3>" . $data["message"] . "</h3>";

    if ($data["is_error"]) {
        $error_specific_location = "<div>at <strong>" . $data["file"] . " </strong> in line <strong> " . $data["line"] . " </strong></div>";
        echo $error_specific_location;
    }
    echo "<div><h3>STACK TRACE:</h3><ul>";
    foreach ($data["trace"] as $index => $item) {
        $item_args = "";
        if (!empty($item["args"])) {
            foreach ($item["args"] as $args => $value) {
                $item_args .= print_r($value, true) . ', ';
            }
            $item_args = rtrim($item_args, ',');
        }
        echo '<li style="margin-bottom: 20px;font-size:20px;"> #' . $index . ': At function <code> ' . (!empty($item["class"]) ? $item["class"] . "->" : false) . $item["function"] . '(args=[' . (!empty($item_args) ?  htmlentities($item_args)  : "") . '])</code> in "' . $item["file"] . '", line ' . $item["line"] . '.</li>';
    }

    echo "</div><h3> RAW TRACE: </h3><div><span>" . $data["traceAsString"] . "</span></div>";

    ?>
</body>

</html>