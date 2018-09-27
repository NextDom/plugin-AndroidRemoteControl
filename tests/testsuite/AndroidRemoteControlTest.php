<?php

/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

use PHPUnit\Framework\TestCase;

class AndroidRemoteControlTest extends TestCase
{
    public function testClassDeclaration()
    {
        include_once('core/class/AndroidRemoteControl.class.php');
        $this->assertTrue(class_exists('AndroidRemoteControl'));
        $methods = get_class_methods('AndroidRemoteControl');
        $this->assertContains('cron', $methods);
        $this->assertContains('toHtml', $methods);
        $this->assertTrue(class_exists('AndroidRemoteControlCmd'));
        $methodsCmd = get_class_methods('AndroidRemoteControlCmd');
        $this->assertContains('execute', $methodsCmd);
    }
}
