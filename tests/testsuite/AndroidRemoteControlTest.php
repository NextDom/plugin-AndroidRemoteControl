<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
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
