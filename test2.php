<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION EMPLOYER</title>
    <style>
        /* style/style.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #841c1c;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        .tab-container {
            text-align: center;
            margin-top: 20px;
        }

        .tab-button {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border: none;
            background-color: #841c1c;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .tab-button:hover {
            background-color: #0056b3;
        }

        .form-container {
            margin: 20px auto;
            width: 100%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-content {
            display: none;
        }

        .form-content.show {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>ENREGISTREMENT</h1>
    </header>
    <div class="tab-container">
        <button class="tab-button" data-target="form1">Utilisateur</button>
        <button class="tab-button" data-target="form2">Employer</button>
    </div>
    <div class="form-container">
        <section id="form1" class="form-content" style="display: none;">
    </div>
</body>

</html>