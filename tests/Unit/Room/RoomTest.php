<?php

namespace Tests\Unit\Room;

use Ratchet\ConnectionInterface;
use TicTacToe\Room\Room;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{

    private Room $room;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|ConnectionInterface
     */
    private $connectionMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->connectionMock = $this->getMockForAbstractClass(ConnectionInterface::class);

        $this->room = new Room(1);
        $this->room->join(1, $this->connectionMock);
        $this->room->join(2, $this->connectionMock);

    }

    public function testGetPlayerById()
    {
        self::assertSame($this->room->getPlayer1(), $this->room->getPlayerById(1));
    }

    public function testGetPlayer2()
    {
        self::assertSame($this->room->getPlayerById(2), $this->room->getPlayer2());
    }

    public function testGetId()
    {
        self::assertSame(1, $this->room->getId());
    }

    public function testRemovePlayerFromRoom()
    {
        $this->room->removePlayerFromRoom(2);
        self::assertCount(1, $this->room->getPlayersInRoom());
    }

    public function testSend()
    {
        $jsonMsg = json_encode([
            'hello' => 'world'
        ]);

        $this->connectionMock->expects(self::exactly(2))->method('send')->with($jsonMsg);

        $this->room->send('hello', 'world');
    }

    public function testJoin()
    {
        $this->room->join(3, $this->connectionMock);
        self::assertCount(3, $this->room->getPlayersInRoom());
    }

    public function testGetPlayer1()
    {
        self::assertSame($this->room->getPlayerById(1), $this->room->getPlayer1());
    }

    public function testGetPlayersInRoom()
    {
        $players = $this->room->getPlayersInRoom();
        self::assertCount(2, $players);

        self::assertSame($players[1]->getPlayerChar(), 'o');
        self::assertSame($players[2]->getPlayerChar(), 'x');
    }
}
