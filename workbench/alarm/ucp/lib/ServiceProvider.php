<?php
namespace UCP;
 
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
 
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $defer = true;
 
    /**
     * {@inheritDoc}
     */
    public function register()
    {
    }
    
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
    }
}