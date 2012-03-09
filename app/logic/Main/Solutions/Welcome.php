<?php
namespace Main\Solutions;

class Welcome
{
    public function to()
    {
        $welcome = 'Welcome to Batman-PHP';

        if (file_exists('README.md'))
        {
            $readme = file('README.md');
            $version = ltrim($readme[3], '#');
            return "<h2>$welcome</h2>$version";
        }
        else
        {
            return "<h2>$welcome</h2>";
        }
    }
}