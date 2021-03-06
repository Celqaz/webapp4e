<?php
# 出现Flash Message
function flashMsg()
{
    if (isset($_SESSION["success"])) {
        echo('<p style="color:green">'.htmlentities($_SESSION["success"])."</p>\n");
        unset($_SESSION["success"]);
    };
    if (isset($_SESSION["error"])) {
        echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
        unset($_SESSION["error"]);
    };
}
# 验证用户输入有效性
function validateProfile()
{
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1
  ||  strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        return 'All values are required';
    }

    if (strpos($_POST['email'], '@') === false) {
        return 'Email address must contain @';
    }
    return  true;
}
# 插入数据
function insertData($pdo)
{
    $sql = "INSERT INTO profile(user_id, first_name, last_name, email, headline, summary)
  VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
      ':user_id' => $_SESSION['user_id'],
      ':first_name' => $_POST['first_name'],
      ':last_name' => $_POST['last_name'],
      ':email' => $_POST['email'],
      ':headline' => $_POST['headline'],
      ':summary' => $_POST['summary']
    )
    );
    # Get the Primary key
    $profile_id = $pdo->lastInsertId();

    # loop to insert
    $rank = 1;
    for ($i=1; $i <= 9  ; $i++) {
        if (! isset($_POST['year'.$i])) {
            continue;
        }
        if (! isset($_POST['desc'.$i])) {
            continue;
        }
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $sql = 'INSERT INTO `position`(`profile_id`, `rank`, `year`, `description`)
        VALUES (:profile_id, :rank, :year, :desc)';
        //     $sql = "INSERT INTO position(profile_id, rank, year, description)
        // VALUES (37, 1, 9999, 'TOP-desc')";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute(
            array(
               ':profile_id' => $profile_id,
               ':rank' => $rank,
               ':year' => $year,
               ':desc' => $desc
               )
        );
        $rank++;
    }# for loop
};

// 验证加载的表单
function validatePos()
{
}
