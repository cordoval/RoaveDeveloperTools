<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace RoaveTest\DeveloperTools\Renderer;

use PHPUnit_Framework_TestCase;
use Roave\DeveloperTools\Inspection\AggregateInspection;
use Roave\DeveloperTools\Inspection\InspectionInterface;
use Roave\DeveloperTools\Inspection\TimeInspection;
use Roave\DeveloperTools\Renderer\DetailInspectionRenderer;
use Zend\View\Model\ModelInterface;

/**
 * Tests for {@see \Roave\DeveloperTools\Renderer\DetailInspectionRenderer}
 *
 * @covers \Roave\DeveloperTools\Renderer\DetailInspectionRenderer
 */
class DetailInspectionRendererTest extends PHPUnit_Framework_TestCase
{
    public function testAcceptsOnlyAggregateInspection()
    {
        $renderer = new DetailInspectionRenderer([]);

        $this->assertTrue($renderer->canRender($this->getMock(InspectionInterface::class)));
        $this->assertTrue($renderer->canRender($this->getMock(TimeInspection::class, [], [], '', false)));
        $this->assertTrue($renderer->canRender($this->getMock(AggregateInspection::class, [], [], '', false)));
    }

    public function testRender()
    {
        $renderer   = new DetailInspectionRenderer([]);
        $inspection = $this->getMock(InspectionInterface::class);
        $viewModel  = $renderer->render($inspection);

        $this->assertInstanceOf(ModelInterface::class, $viewModel);

        $this->assertSame($inspection, $viewModel->getVariable('inspection'));
    }
}
