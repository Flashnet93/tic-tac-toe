<?php

namespace Tests\Unit\Game;

use PHPUnit\Framework\MockObject\MockObject;
use Ratchet\ConnectionInterface;
use TicTacToe\Game\Game;
use PHPUnit\Framework\TestCase;
use TicTacToe\Player\Player;
use TicTacToe\Room\Room;

class GameTest extends TestCase
{
    private Game $game;
    private Room $room;

    /**
     * @var MockObject|ConnectionInterface
     */
    private $connectionMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->connectionMock = $this->getMockForAbstractClass(ConnectionInterface::class);

        $this->room = new Room(1);
        $this->room->join(1, $this->connectionMock);
        $this->room->join(2, $this->connectionMock);

        $this->game = new Game($this->room->getPlayer1(), $this->room->getPlayer2(), $this->room);
    }

    public function testSendWinnerToAllPlayers()
    {
        $winMessage = 'Player 1 is the greatest';
        $jsonMsg = json_encode([
            'win' => $winMessage
        ]);

        $this->connectionMock->expects(self::exactly(2))->method('send')->with($jsonMsg);

        $this->game->sendWinnerToAllPlayers($winMessage);
    }

    public function testGetPlayer1()
    {
        self::assertSame($this->room->getPlayer1(), $this->game->getPlayer1());
    }

    public function testResetField()
    {
        $player1 = $this->game->getPlayer1();

        $this->game->setField(1, $player1);

        $this->game->resetField();

        self::assertSame([
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
            7 => '',
            8 => '',
            9 => '',
        ], $this->game->getField());

    }

    public function testGetRoom()
    {
        self::assertSame($this->room, $this->game->getRoom());
    }

    public function testSetField()
    {
        $player1 = $this->game->getPlayer1();
        $player2 = $this->game->getPlayer2();

        $this->game->setField(1, $player1);
        $this->game->setField(2, $player2);
        $this->game->setField(3, $player1);
        $this->game->setField(4, $player2);
        $this->game->setField(5, $player1);
        $this->game->setField(6, $player2);
        $this->game->setField(7, $player1);
        $this->game->setField(8, $player2);
        $this->game->setField(9, $player1);

        self::assertSame([
            1 => 'o',
            2 => 'x',
            3 => 'o',
            4 => 'x',
            5 => 'o',
            6 => 'x',
            7 => 'o',
            8 => 'x',
            9 => 'o',
        ], $this->game->getField());

    }

    public function testGetPlayer2()
    {
        self::assertSame($this->room->getPlayer1(), $this->game->getPlayer1());
    }

    public function testSetGameOver()
    {
        $jsonMsg = json_encode([
            'info' => 'Game already over.'
        ]);

        $this->connectionMock->expects(self::once())->method('send')->with($jsonMsg);
        $this->game->setGameOver(true);
        $this->game->setField(1, $this->room->getPlayer1());
    }

    public function testCheckFieldIsFull()
    {
        $player1 = $this->game->getPlayer1();
        $player2 = $this->game->getPlayer2();

        $this->game->setField(1, $player1);
        $this->game->setField(2, $player2);
        $this->game->setField(3, $player1);
        $this->game->setField(4, $player2);
        $this->game->setField(5, $player1);
        $this->game->setField(6, $player2);
        $this->game->setField(7, $player1);
        $this->game->setField(8, $player2);
        $this->game->setField(9, $player1);

        self::assertTrue($this->game->checkFieldIsFull());
    }

    public function testSendFieldToAllPlayers()
    {
        $player1 = $this->game->getPlayer1();
        $player2 = $this->game->getPlayer2();

        $this->game->setField(1, $player1);
        $this->game->setField(2, $player2);
        $this->game->setField(3, $player1);
        $this->game->setField(4, $player2);
        $this->game->setField(5, $player1);
        $this->game->setField(6, $player2);
        $this->game->setField(7, $player1);
        $this->game->setField(8, $player2);
        $this->game->setField(9, $player1);


        $jsonMsg = json_encode([
            'field' => $this->game->getField()
        ]);

        $this->connectionMock->expects(self::exactly(2))->method('send')->with($jsonMsg);

        $this->game->sendFieldToAllPlayers();
    }

    public function testGetField()
    {
        $player1 = $this->game->getPlayer1();
        $this->game->setField(5, $player1);

        self::assertSame([
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => 'o',
            6 => '',
            7 => '',
            8 => '',
            9 => '',
        ], $this->game->getField());
    }

    public function testGetPlayerByChar()
    {
        var_dump($this->game->getPlayer1()->getPlayerChar());
        var_dump($this->game->getPlayer2()->getPlayerChar());
        self::assertSame($this->game->getPlayer1(), $this->game->getPlayerByChar(Player::PLAYER_O));
        self::assertSame($this->game->getPlayer2(), $this->game->getPlayerByChar(Player::PLAYER_X));
    }

}
