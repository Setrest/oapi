<?php

namespace Setrest\OAPIDocumentation;

use Setrest\OAPIDocumentation\Router\ResponseSpec;
use Setrest\OAPIDocumentation\Router\RouteSpec;
use Exception;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class RouterService
{
    /** @var Router */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getApiRoutes(): array
    {
        $routes = [];
        foreach ($this->router->getRoutes()->getRoutes() as $route) {
            if (!in_array(config('oapidocs.api_middleware'), $route->middleware())) {
                continue;
            }

            $routes[] = $route;
        }

        usort($routes, function (Route $a, Route $b) {
            return strnatcmp($a->uri(), $b->uri());
        });

        return $routes;
    }

    public function parseRoutes(array $routes): array
    {
        $rows = [];

        foreach ($routes as $group => $route) {

            $rows[$group] = [];

            try {
                $reflection = new ReflectionClass($route->getController());
            } catch (Exception $e) {
                continue;
            }

            $routeSpec = new RouteSpec($route->uri(), $this->getRouteMethods($route), in_array('guest', $route->middleware()));

            if ($tag = $this->getPartFromComment('category', $reflection->getDocComment())) {
                $routeSpec->addTag($tag);
            }

            $methodName = Str::parseCallback($route->getAction('uses'))[1];
            $reflectionMethod = $reflection->getMethod($methodName);
            $methodComment =  $reflectionMethod->getDocComment();

            if ($summary = $this->getPartFromComment('name', $methodComment)) {
                $routeSpec->addSummary($summary);
            }

            if ($methodTag = $this->getPartFromComment('category', $methodComment)) {
                $routeSpec->addTag($methodTag);
            }

            $routeSpec->addRules($this->getRequestParam($reflectionMethod));

            if ($response = $this->getResponse($reflectionMethod)) {
                $routeSpec->addResponse($response);
            }

            $rows[$group][] = $routeSpec;
        }

        return $rows;
    }

    private function getRouteMethods($route): array
    {
        $methods = $route->methods();
        $hideHeadMethods = config('hide_head') ?? true;
        
        if ($hideHeadMethods) {
            unset($methods[array_search('HEAD', $methods)]);
        }

        return $methods;
    }

    public function getRequestParam(ReflectionMethod $method)
    {
        $validationFields = null;

        foreach ($method->getParameters() as $param) {

            if ($param->getType() === null) {
                continue;
            }

            $paramName = $param->getType()->getName();

            if (stripos($paramName, 'App\Http\Requests') === false) {
                continue;
            }

            $rf = new ReflectionClass($paramName);

            $methodTmp = $rf->getMethod('rules');
            $validationFields = $methodTmp->invokeArgs(new $paramName, []);
            break;
        }

        return $validationFields;
    }

    public function getResponse(ReflectionMethod $method): ?ResponseSpec
    {
        $methodLines = $this->parseFile($method->getFileName(), $method->getStartLine(), $method->getEndLine());
        $responseLines = $this->findResponse($methodLines);

        return ResponseSpec::findByResponses($responseLines, $methodLines);
    }

    private function parseFile(string $file, int $lineFrom, int $lineTo): array
    {
        $lines = [];
        $currentLine = 0;
        $controller = fopen($file, 'r');

        while($line = fgets($controller)) {
            $currentLine++;

            if ($currentLine < $lineFrom) {
                continue;
            } elseif ($currentLine > $lineTo) {
                break;
            }

            $lines[] = $line;
        }

        return $lines;
    }

    private function findResponse(array $methodLines): array
    {
        $responseLines = [];
        $founded = false;
        foreach ($methodLines as $line) {
            if (stripos($line, 'return') === false && !$founded) {
                continue;
            }

            $founded = true;
            $responseLines[] = trim(str_replace('}','',$line));
            $responseLines = array_filter($responseLines);
        }

        return $responseLines;
    }

    public function getPartFromComment(string $part, string $comment): ?string
    {
        $parsedComment = $this->parseDocComment($comment);

        if ($part === 'name') {
            $name = array_shift($parsedComment);
            return $name && $name[0] !== '@' ? $name : null;
        }

        $part = $part[0] === '@' ? $part : '@' . $part;

        foreach ($parsedComment as $line) {
            if (stripos($line, $part) !== false) {
                return trim(str_replace($part, '', $line));
            }
        }

        return null;
    }

    public function parseDocComment(string $comment): ?array
    {
        $matches = null;
        $pattern = "#([a-zA-Z+@]+\s*[a-zA-Z0-9, ()_].*)#";
        preg_match_all($pattern, $comment, $matches, PREG_PATTERN_ORDER);

        return $matches[0] ?? null;
    }
}
