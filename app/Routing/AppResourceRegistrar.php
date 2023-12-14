<?php

namespace App\Routing;

use Illuminate\Routing\ResourceRegistrar;

class AppResourceRegistrar extends ResourceRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'edit', 'update', 'approve', 'pending', 'reject'];



    /**
     * Add the finalize method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourcePending($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/finalize';

        $action = $this->getResourceAction($name, $controller, 'finalize', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * Add the approve method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceApprove($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/approve';

        $action = $this->getResourceAction($name, $controller, 'approve', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * Add the reject method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceReject($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/reject';

        $action = $this->getResourceAction($name, $controller, 'reject', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * Add the missing method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceMissing($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/missing';

        $action = $this->getResourceAction($name, $controller, 'missing', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the missing method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceExport($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/export';

        $action = $this->getResourceAction($name, $controller, 'export', $options);

        return $this->router->get($uri, $action);
    }
}
