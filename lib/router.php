<?php
class Router
{
    private $routes = [];

    public function add(string $method, string $pattern, callable $fn): void
    {
        $re = '#^' . preg_replace('#:([A-Za-z_]+)#', '(?P<$1>[^/]+)', $pattern) . '$#';
        $this->routes[] = [$method, $re, $fn];
    }

    public function get(string $pattern, callable $fn): void
    {
        $this->add('GET', $pattern, $fn);
    }

    public function post(string $pattern, callable $fn): void
    {
        $this->add('POST', $pattern, $fn);
    }

    public function match(string $method, string $path): ?array
    {
        foreach ($this->routes as $r) {
            if ($r[0] === $method && preg_match($r[1], $path, $mm)) {
                $params = [];
                foreach ($mm as $k => $v) {
                    if (is_string($k)) $params[$k] = $v;
                }
                return [$r[2], $params];
            }
        }
        return null;
    }
}
