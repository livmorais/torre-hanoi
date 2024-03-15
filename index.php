<?php
session_start();

function iniciarJogo($discos) {
    $_SESSION['torre1'] = $discos > 0 ? range($discos, 1, -1) : [];
    $_SESSION['torre2'] = [];
    $_SESSION['torre3'] = [];
    $_SESSION['movimentacoes'] = 0;
    $_SESSION['limiteMovimentacoes'] = $discos > 0 ? pow(2, $discos) - 1 : 0;
}

if ((isset($_POST['startButton']) && !isset($_POST['resetButton'])) || (!isset($_SESSION['torre1']) && !isset($_POST['resetButton']))) {
    $discos = isset($_POST['diskNumber']) ? (int)$_POST['diskNumber'] : 3;
    if ($discos >= 2 && $discos <= 10) {
        iniciarJogo($discos);
        $_SESSION['diskNumber'] = $discos;
    }
}

if (isset($_POST['resetButton'])) {
    iniciarJogo(0);  
    unset($_SESSION['diskNumber']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


function renderizarDisco($disco, $torre, $totalDiscos) {
    $largura = (($disco / $totalDiscos) * 100) + 150 + pow($disco, 2); 
    echo '<div class="disk disk' . $disco . '" id="disco' . $disco . 'torre' . $torre . '" draggable="true" style="width:' . $largura . '%"></div>';
}


function renderizarTorre($torre, $totalDiscos) {
    if (isset($_SESSION[$torre])) {
        echo '<div class="tower" id="' . $torre . '">';
        foreach ($_SESSION[$torre] as $disco) {
            renderizarDisco($disco, $torre, $totalDiscos);
        }
        echo '</div>';
    }
}

if (!isset($_SESSION['torre1']) || isset($_POST['startButton'])) {
    $discos = isset($_POST['diskNumber']) ? (int)$_POST['diskNumber'] : 3;
    if ($discos >= 2 && $discos <= 10) {
        iniciarJogo($discos);
        $_SESSION['diskNumber'] = $discos;
    }
}

if (isset($_POST['incrementMove'])) {
    $_SESSION['movimentacoes']++;
    echo $_SESSION['movimentacoes'];
    exit;
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torre de Hanói</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="game-container">
    <div class="base"></div>
        <?php
        if (isset($_SESSION['torre1'])) {
            $totalDiscos = count($_SESSION['torre1']);
            renderizarTorre('torre1', $totalDiscos);
            renderizarTorre('torre2', $totalDiscos);
            renderizarTorre('torre3', $totalDiscos);
        }
        ?>
    </div>
    
    <div class="controls">
        <form method="post" action="">
            <label for="diskNumber">Número de Discos:</label>
            <input type="number" name="diskNumber" min="2" max="10"
            value="<?php echo isset($_SESSION['diskNumber']) ? $_SESSION['diskNumber'] : ''; ?>">
            <input type="submit" name="startButton" value="Iniciar Jogo">
            <input type="submit" name="simulateButton" value="Simular Jogo">
            <input type="submit" name="resetButton" value="Reiniciar">
        </form>
        <?php if (isset($_SESSION['movimentacoes']) && isset($_SESSION['limiteMovimentacoes'])): ?>
            <p id="movimentosRealizados">Movimentos realizados: <?php echo $_SESSION['movimentacoes']; ?></p>
            <p>Limite de movimentos: <?php echo $_SESSION['limiteMovimentacoes']; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>