const rows = 30, cols = 30;
let grid = [];
let interval = null;
let generation = 0;

function createGrid() {
  const gridDiv = document.getElementById("grid");
  gridDiv.innerHTML = "";
  grid = [];

  for (let r = 0; r < rows; r++) {
    const row = [];
    for (let c = 0; c < cols; c++) {
      const cell = document.createElement("div");
      cell.className = "cell";
      cell.dataset.row = r;
      cell.dataset.col = c;
      cell.addEventListener("click", () => toggleCell(r, c));
      gridDiv.appendChild(cell);
      row.push(false);
    }
    grid.push(row);
  }
  updatePopulation();
}

function toggleCell(r, c) {
  grid[r][c] = !grid[r][c];
  drawGrid();
  updatePopulation();
}

function drawGrid() {
  const cells = document.querySelectorAll(".cell");
  cells.forEach(cell => {
    const r = +cell.dataset.row;
    const c = +cell.dataset.col;
    cell.classList.toggle("alive", grid[r][c]);
  });
}

function getLiveNeighbors(r, c) {
  let count = 0;
  for (let dr = -1; dr <= 1; dr++) {
    for (let dc = -1; dc <= 1; dc++) {
      if (dr === 0 && dc === 0) continue;
      const nr = r + dr, nc = c + dc;
      if (nr >= 0 && nr < rows && nc >= 0 && nc < cols) {
        if (grid[nr][nc]) count++;
      }
    }
  }
  return count;
}

function nextGeneration() {
  const newGrid = grid.map(row => [...row]);

  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      const live = grid[r][c];
      const neighbors = getLiveNeighbors(r, c);

      if (live && (neighbors < 2 || neighbors > 3)) newGrid[r][c] = false;
      else if (!live && neighbors === 3) newGrid[r][c] = true;
    }
  }

  grid = newGrid;
  generation++;
  drawGrid();
  updatePopulation();
  document.getElementById("generation").textContent = generation;
}

function run23Generations() {
  for (let i = 0; i < 23; i++) nextGeneration();
}

function startGame() {
  if (!interval) {
    interval = setInterval(nextGeneration, 200);
  }
}

function stopGame() {
  clearInterval(interval);
  interval = null;
}

function resetGame() {
  generation = 0;
  createGrid();
  document.getElementById("generation").textContent = generation;
}

function updatePopulation() {
  let count = 0;
  grid.forEach(row => row.forEach(cell => { if (cell) count++; }));
  document.getElementById("population").textContent = count;
}

function loadPattern(name) {
  resetGame();
  const pattern = patterns[name];
  if (!pattern) return;
  const offsetX = Math.floor(rows / 2);
  const offsetY = Math.floor(cols / 2);

  pattern.forEach(([r, c]) => {
    if (grid[offsetX + r] && grid[offsetX + r][offsetY + c] !== undefined) {
      grid[offsetX + r][offsetY + c] = true;
    }
  });
  drawGrid();
  updatePopulation();
}

createGrid();
