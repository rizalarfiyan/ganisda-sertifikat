<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ArrayAccess;

class SessionMiddleware implements Middleware, ArrayAccess
{
    private $storage;

    public function __construct()
    {
        session_start();
        $this->storage =& $_SESSION;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!isset($this->storage['logged'])) {
            $this->storage['logged'] = false;
        }

        $request = $request->withAttribute('session', $this);
        $request = $request->withAttribute('uinfo', array_key_exists('uinfo', $this->storage) ? $this->storage['uinfo'] : null);
        return $handler->handle($request);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->storage[] = $value;
        } else {
            $this->storage[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->storage[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
    }

    public function &offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return  $this->storage[$offset];
        }
        return null;
    }
}
