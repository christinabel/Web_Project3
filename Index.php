<?php
session_start();
require './config/connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

// Log a game session (once per session)
if (!isset($_SESSION['game_session_id'])) {
    $stmt = $conn->prepare("INSERT INTO game_sessions (user_id, start_time, generations) VALUES (?, NOW(), 0)");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $_SESSION['game_session_id'] = $conn->insert_id;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Conway's Game of Life</title>
  <link rel="stylesheet" href="Grid_styles.css">
</head>

<body>

  <!-- Header and Introduction -->
  <header>
    <h1>Conway's Game of Life</h1>
    <p>
      Discover the fascinating world of cellular automata! Conway’s Game of Life is a zero-player game where patterns
      evolve based on simple rules. It simulates birth, survival, and death on a grid — showing how complexity can arise
      from simplicity.
    </p>
    <p><strong>Rules:</strong></p>
    
      <li>A live cell with 2 or 3 neighbors survives.</li>
      <li>A dead cell with exactly 3 neighbors becomes alive.</li>
      <li>All other cells die or remain dead.</li>
    
    <p><strong>Ready to see life unfold?</strong></p>
    <button onclick="startGame()">👉 Try It Now</button>
  </header>

  <!-- Navigation -->
  <nav>
    <a href="#grid">Play Game</a> 
    <a href="homepage.html">Logout</a></p>
  </nav>

  <!-- Controls -->
  <section id="controls">
    <label>
      Grid Size:
      <input type="number" id="gridSize" value="30" min="10" max="100" />
    </label>

    <label>
      Speed (ms):
      <input type="number" id="speed" value="200" min="50" />
    </label>

    <label>
      <input type="checkbox" id="animateToggle" checked />
      Animate
    </label>

    <br><br>

    <button onclick="startGame()">▶️ Start</button>
    <button onclick="stopGame()">⏸ Stop</button>
    <button onclick="nextGeneration()">⏭ Next</button>
    <button onclick="run23Generations()">🧑 +23 Generations</button>
    <button onclick="resetGame()">♻️ Reset</button>

    <select id="patternSelector" onchange="loadPattern(this.value)">
      <option value="">🧑 Load Pattern</option>
      <optgroup label="Still Life">
        <option value="block">Block</option>
        <option value="boat">Boat</option>
        <option value="beehive">Beehive</option>
      </optgroup>
      <optgroup label="Oscillators">
        <option value="blinker">Blinker</option>
        <option value="beacon">Beacon</option>
      </optgroup>
    </select>
  </section>

  <!-- Info and Dynamic Preview -->
  <section id="info">
    <span>Generation: <span id="generation">0</span></span>
    <span>Population: <span id="population">0</span></span>
  </section>

  <div id="grid"></div> <!-- This is the dynamic preview -->

 

  <!-- Scripts -->
  <script src="patterns.js"></script>
  <script src="game.js"></script>
</body>

</html>