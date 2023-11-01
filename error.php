<!DOCTYPE html>
<html>
<head>
  <title>Page Not Found</title>
  <style>
    body{
    background-image: url('assets/images/error.png');
background-size: 100%;
background-repeat: round;
background-attachment: fixed;
max-width: 100%;
max-height: 100%;
color:white;
}
    button {
      text-decoration: none;
      display: inline-block;
      padding: 8px 16px;
      margin-top: 10px;
      background-color: #f1f1f1;
      color: black;
      border: 1px solid #ccc;
      cursor: pointer;
    }

    button:hover {
      background-color: #ddd;
    }
    .button {
  padding: 0.6em 2em;
  border: none;
  outline: none;
  color: rgb(255, 255, 255);
  background: #111;
  cursor: pointer;
  position: relative;
  z-index: 0;
  border-radius: 10px;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button:before {
  content: "";
  background: linear-gradient(
    45deg,
    #ff0000,
    #ff7300,
    #fffb00,
    #48ff00,
    #00ffd5,
    #002bff,
    #7a00ff,
    #ff00c8,
    #ff0000
  );
  position: absolute;
  top: -2px;
  left: -2px;
  background-size: 400%;
  z-index: -1;
  filter: blur(5px);
  -webkit-filter: blur(5px);
  width: calc(100% + 4px);
  height: calc(100% + 4px);
  animation: glowing-button-85 20s linear infinite;
  transition: opacity 0.3s ease-in-out;
  border-radius: 10px;
}

@keyframes glowing-button-85 {
  0% {
    background-position: 0 0;
  }
  50% {
    background-position: 400% 0;
  }
  100% {
    background-position: 0 0;
  }
}

.button:after {
  z-index: -1;
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  background: #222;
  left: 0;
  top: 0;
  border-radius: 10px;
}
.error_statement {
    margin: 163px 50px;
    font-size: 20px;
}
  </style>
</head>
<body>
    <div class="error_statement">
  <h1>Page Not Found</h1>
  <p>
  We’re sorry,but an error occurred while processing your request.<br>
Please try again later or contact support if the problem persists.</p>

  <button type="button" class="button" onclick="goBack()">Back</button>
  </div>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>
</body>
</html>
