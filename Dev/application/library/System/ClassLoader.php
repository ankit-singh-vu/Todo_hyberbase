<?php
/**
 * All HyperBase code is Copyright 2001 - 2012 by the original authors.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program as the file LICENSE.txt; if not, please see
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
 * 
 * HyperBase is a registered trademark of Dyutiman Chakraborty.
 *
 * HyperBase includes works under other copyright notices and distributed
 * according to the terms of the GNU General Public License or a compatible
 * license.
 * 
 */

namespace System;

/**
 * ClassLoader implements a "universal" autoloader for PHP 5.3.
 *
 * It is able to load classes that use either:
 *
 *  * The technical interoperability standards for PHP 5.3 namespaces and
 *    class names (http://groups.google.com/group/php-standards/web/psr-0-final-proposal);
 *
 *  * The PEAR naming convention for classes (http://pear.php.net/).
 *
 * Classes from a sub-namespace or a sub-hierarchy of PEAR classes can be
 * looked for in a list of locations to ease the vendoring of a sub-set of
 * classes for large projects.
 *
 * Example usage:
 *
 *     $loader = new UniversalClassLoader();
 *
 *     // register classes with namespaces
 *     $loader->registerNamespaces(array(
 *       'Symfony\Component' => __DIR__.'/component',
 *       'Symfony' => __DIR__.'/framework',
 *     ));
 *
 *     // register a library using the PEAR naming convention
 *     $loader->registerPrefixes(array(
 *       'Swift_' => __DIR__.'/Swift',
 *     ));
 *
 *     // activate the autoloader
 *     $loader->register();
 *
 * In this example, if you try to use a class in the Symfony\Component
 * namespace or one of its children (Symfony\Component\Console for instance),
 * the autoloader will first look for the class under the component/
 * directory, and it will then fallback to the framework/ directory if not
 * found before giving up.
 * 
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :6.0
 * @author      :Dyutiman Chakraborty <dyutiman@mclogics.com> 
 *
 */
class ClassLoader
{
    protected $namespaces = array();
    protected $prefixes = array();

    public function getNamespaces()
    {
        return $this->namespaces;
    }
    
    public function getNamespacePaths($ns)
    {
        if(isset($this->namespaces[$ns])) {
            return $this->namespaces[$ns];
        }
        return false;
    }

    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * Registers an array of namespaces
     *
     * @param array $namespaces An array of namespaces (namespaces as keys and locations as values)
     */
    public function registerNamespaces(array $namespaces)
    {
        foreach($namespaces as $ns => $path) {
            $this->registerNamespace($ns, $path);
        }
        return $this;
    }

    /**
     * Registers a namespace.
     *
     * @param string $namespace The namespace
     * @param mixed $path      The location of the namespace (string or array of possible paths)
     */
    public function registerNamespace($namespace, $path)
    {
        // If namespace has already been specified, add path to array of possible paths -- don't overwrite
        if(isset($this->namespaces[$namespace])) {
            $path = array_merge($this->namespaces[$namespace], (array) $path);
        }
        $this->namespaces[$namespace] = (array) $path;
        return $this;
    }

    /**
     * Registers an array of classes using the PEAR naming convention.
     *
     * @param array $classes An array of classes (prefixes as keys and locations as values)
     */
    public function registerPrefixes(array $classes)
    {
        foreach($classes as $prefix => $path) {
            $this->registerPrefix($prefix, $path);
        }
        return $this;
    }

    /**
     * Registers a set of classes using the PEAR naming convention.
     *
     * @param string $prefix The classes prefix
     * @param mixed $path   The location of the classes (string or array or possible paths)
     */
    public function registerPrefix($prefix, $path)
    {
        // If prefix has already been specified, add path to array of possible paths -- don't overwrite
        if (isset($this->prefixes[$prefix])) {
            $path = array_merge($this->prefixes[$prefix], (array) $path);
        }
        $this->prefixes[$prefix] = (array) $path;
        return $this;
    }

    /**
     * Registers this instance as an autoloader.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
        return $this;
    }

    public function registerComposerLibraries()
    {
        if (file_exists('../vendor/autoload.php')) {
            return include '../vendor/autoload.php';
        }
        return false;
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     */
    public function loadClass($class)
    {
               
        if (false !== ($pos = strripos($class, '\\'))) {
            // namespaced class name
            $namespace = substr($class, 0, $pos);
            $class = substr($class, $pos + 1);
            foreach ($this->namespaces as $ns => $dirs) {
                $dirs = array_reverse($dirs);
                foreach($dirs as $dir) {
                    if (0 === strpos($namespace, $ns)) {
                        $file = $dir.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR.str_replace('_', DIRECTORY_SEPARATOR, $class).'.php';
                        if (file_exists($file)) {
                            return require_once $file;
                        }
                    }
                }
            }
        } else {
            // PEAR-like class name
            foreach ($this->prefixes as $prefix => $dirs) {
                $dirs = array_reverse($dirs);
                foreach($dirs as $dir) {
                    if (0 === strpos($class, $prefix)) {
                        $file = $dir.DIRECTORY_SEPARATOR.str_replace('_', DIRECTORY_SEPARATOR, $class).'.php';
                        if (file_exists($file)) {
                            return require_once $file;
                        }
                    }
                }
            }
        }
    }
}
