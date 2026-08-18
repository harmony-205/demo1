<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Thêm sinh viên</h1>
    <form action="" method="POST">
        <label for="ma_sv">Mã sinh viên:</label>
        <input type="text" id="ma_sv" name="ma_sv" required><br><br>

        <label for="ho_ten">Họ tên:</label>
        <input type="text" id="ho_ten" name="ho_ten" required><br><br>

        <label for="ngay_sinh">Ngày sinh:</label>
        <input type="date" id="ngay_sinh" name="ngay_sinh" required><br><br>

        <label for="lop">Lớp:</label>
        <input type="text" id="lop" name="lop" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Thêm sinh viên</button>
</body>
</html>