<?php

declare(strict_types=1);

namespace TicTacToe\Player;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsConnection;

class Player
{

    private int $resourceId;
    private string $playerChar;
    private ConnectionInterface $webSocket;

    /**
     * Player constructor.
     *
     * @param $resourceId
     * @param ConnectionInterface $webSocket
     */
    public function __construct($resourceId, ConnectionInterface $webSocket)
    {
        $this->resourceId = $resourceId;
        $this->webSocket = $webSocket;
    }

    /**
     * Returns the character of the player (x/o)
     *
     * @return string
     */
    public function getPlayerChar(): string
    {
        return $this->playerChar;
    }

    /**
     * Sets the character for the player
     *
     * @param string $playerChar
     *
     * @return void
     */
    public function setPlayerChar(string $playerChar): void
    {
        $this->playerChar = $playerChar;
    }

    /**
     * Send message to all players in the room.
     *
     * @param string $type Type of the message (info, error, win, playerChar...).
     * @param mixed $message Message to send. Can also be the field array.
     *
     * @return void
     */
    public function send(string $type, $message): void
    {
        $jsonMsg = json_encode([
            $type => $message
        ]);

        $this->webSocket->send($jsonMsg);
    }

    /**
     * Returns the player object by his player char if correct.
     *
     * @param string $playerChar
     *
     * @return Player|null
     */
    public function getPlayerByChar(string $playerChar): ?Player
    {
        if ($this->getPlayerChar() === $playerChar) {
            return $this;
        }

        return null;
    }

}