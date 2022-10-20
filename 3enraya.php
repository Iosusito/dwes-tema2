<?php

//secillo método para dibujar un tablero y su contenido
function printBoard(array $board): void
{
    echo "+---+---+---+\n" .
        "| " . $board[0][0] . " | " . $board[0][1] . " | " . $board[0][2] . " |\n" .
        "+---+---+---+\n" .
        "| " . $board[1][0] . " | " . $board[1][1] . " | " . $board[1][2] . " |\n" .
        "+---+---+---+\n" .
        "| " . $board[2][0] . " | " . $board[2][1] . " | " . $board[2][2] . " |\n" .
        "+---+---+---+\n";
}

//comprueba si la posición en el tablero está libre u ocupada
function emptyPosition(array $board, int $position): bool
{
    return $board[floor($position / 3.1)][($position - 1) % 3] == " " ? true : false;
}

//comprueba si la posición en el tablero es válida, y esté vacía
function validPosition(array $board, $position): bool
{
    return is_numeric($position) && $position > 0 && $position < 10 && emptyPosition($board, $position) ? true : false;
}

//asigna el valor (x / o) la posición del tablero indicada
function asignValue(array &$board, int $position, string $value): void
{
    $board[floor($position / 3.1)][($position - 1) % 3] = $value;
}

//avanza el turno, alternando entre x / o
function nextTurn(string &$turn): void
{
    $turn = $turn == "x" ? "o" : "x";
}

//comprueba si el turno tinene un tres en raya o no
function wins(array $board, string $turn): bool
{
    $win = false;

    //comprobar filas
    for ($i = 0; !$win && $i < 3; $i++) {
        $win = ($board[$i] == [$turn, $turn, $turn]) ? true : false;
    }

    //comprobar las columnas
    for ($i = 0; !$win && $i < 3; $i++) {
        $win = $board[$i][0] == $turn && $board[$i][1] == $turn && $board[$i][2] == $turn ? true : false;
    }

    if (!$win && (
        //comprobar diagonal izquierda derecha 
        ($board[0][0] == $turn && $board[1][1] == $turn && $board[2][2]) == $turn ||
        //comprobar diagonal derecha izquerda
        ($board[0][2] == $turn && $board[1][1] == $turn && $board[2][0] == $turn)
    )) {
        $win = true;
    }

    return $win;
}

//recorre el tablero y si hay al menos una celda vacía devuelve false
function fullBoard(array $board): bool
{
    $full = true;
    for ($i = 0; $full && $i < sizeof($board); $i++) {
        for ($j = 0; $full && $j < sizeof($board[$i]); $j++) {
            $full = $board[$i][$j] == " " ? false : true;
        }
    }
    return $full;
}

//ciclo del turno, devuelve el ganador, si hay ("x" / "o" / "d" / "")
function TurnCycle(array &$board, string &$turn): string
{
    //empieza el turno
    echo "Turno de " . $turn . "\n";

    //recoger la posicion, validarla y asignarla
    do {
        $position = readline("Escoja la posición: ");
    } while (!validPosition($board, $position)); //se comprueba si la posción está ocupada

    //sabemos que posición es válida para marcar
    asignValue($board, $position, $turn);

    //comprueba si el jugador del turno, x / o, tiene un tres en raya; sino,
    //mira si es un el tablero está lleno para un empate (return "d") y sino
    //sigue la partida (return "") VA MAL
    $winner = wins($board, $turn) ? $turn : (fullBoard($board) ? "d" : ""); //d es por draw, empate

    //mostramos el tablero
    printBoard($board);

    //siguiente turno
    nextTurn($turn);

    return $winner;
}

do {
    //tablero
    $board = [
        [" ", " ", " "],
        [" ", " ", " "],
        [" ", " ", " "]
    ];

    $turn = "x"; //los turnos serán x y o por cada tipo de marca, empiezan x

    echo "Guía para cuadrículas:\n" .
        "+---+---+---+\n" .
        "| 1 | 2 | 3 |\n" .
        "+---+---+---+\n" .
        "| 4 | 5 | 6 |\n" .
        "+---+---+---+\n" .
        "| 7 | 8 | 9 |\n" .
        "+---+---+---+\n";

    //el ciclo de juego, cada iteración es un turno, se acaba al haber un ganador o empate (winner = "d")
    do {
        $winner = TurnCycle($board, $turn);
    } while ($winner == "");

    echo $winner == "d" ? "Ha habido un empate\n" : "Ha ganado: " . $winner . "\n"; ;

    //se pregunta por otra partida
    do {
        $repeat = mb_strtolower(readline("¿Quiere jugar otra partida? Conteste s/n\n"), "utf-8");
    } while ($repeat != "s" && $repeat != "n");
} while ($repeat == "s");