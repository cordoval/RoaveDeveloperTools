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
use Roave\DeveloperTools\Renderer\InspectionRendererInterface;
use Roave\DeveloperTools\Renderer\ToolbarInspectionRenderer;
use Zend\View\Model\ModelInterface;

/**
 * Tests for {@see \Roave\DeveloperTools\Renderer\ToolbarInspectionRenderer}
 *
 * @covers \Roave\DeveloperTools\Renderer\ToolbarInspectionRenderer
 */
class ToolbarInspectionRendererTest extends PHPUnit_Framework_TestCase
{
    public function testAcceptsOnlyAggregateInspection()
    {
        $renderer = new ToolbarInspectionRenderer([]);

        $this->assertFalse($renderer->canRender($this->getMock(InspectionInterface::class)));
        $this->assertFalse($renderer->canRender($this->getMock(TimeInspection::class, [], [], '', false)));
        $this->assertTrue($renderer->canRender($this->getMock(AggregateInspection::class, [], [], '', false)));
    }

    public function testRendersWithEmptyTabRenderers()
    {
        $renderer   = new ToolbarInspectionRenderer([]);
        $inspection = new AggregateInspection([]);
        $viewModel  = $renderer->render($inspection);

        $this->assertInstanceOf(ModelInterface::class, $viewModel);

        $this->assertSame($inspection, $viewModel->getVariable('inspection'));
        $this->assertSame([], $viewModel->getVariable('tabs'));
    }

    public function testRendersWithSingleTabRenderer()
    {
        $tabRenderer        = $this->getMock(InspectionRendererInterface::class);
        $wrappedInspection1 = $this->getMock(InspectionInterface::class);
        $wrappedInspection2 = $this->getMock(InspectionInterface::class);
        $wrappedModel       = $this->getMock(ModelInterface::class);
        $renderer           = new ToolbarInspectionRenderer([$tabRenderer]);
        $inspection         = new AggregateInspection([$wrappedInspection1, $wrappedInspection2]);

        $tabRenderer
            ->expects($this->exactly(2))
            ->method('canRender')
            ->with($this->logicalOr($wrappedInspection1, $wrappedInspection2))
            ->will($this->returnValue(true));

        $tabRenderer
            ->expects($this->exactly(2))
            ->method('render')
            ->with($this->logicalOr($wrappedInspection1, $wrappedInspection2))
            ->will($this->returnValue($wrappedModel));

        $viewModel = $renderer->render($inspection);

        $this->assertCount(2, $viewModel->getChildren());

        $this->assertSame([[$wrappedModel, $wrappedModel]], $viewModel->getVariable('tabs'));
    }

    public function testRendersWithMultipleTabRenderers()
    {
        $tabRenderer1       = $this->getMock(InspectionRendererInterface::class);
        $tabRenderer2       = $this->getMock(InspectionRendererInterface::class);
        $wrappedInspection1 = $this->getMock(InspectionInterface::class);
        $wrappedInspection2 = $this->getMock(InspectionInterface::class);
        $wrappedModel       = $this->getMock(ModelInterface::class);
        $renderer           = new ToolbarInspectionRenderer([$tabRenderer1, $tabRenderer2]);
        $inspection         = new AggregateInspection([$wrappedInspection1, $wrappedInspection2]);

        $tabRenderer1->expects($this->any())->method('canRender')->will($this->returnValue(true));
        $tabRenderer2->expects($this->any())->method('canRender')->will($this->returnValue(true));

        $tabRenderer1->expects($this->any())->method('render')->will($this->returnValue($wrappedModel));
        $tabRenderer2->expects($this->any())->method('render')->will($this->returnValue($wrappedModel));

        $viewModel = $renderer->render($inspection);

        $this->assertCount(4, $viewModel->getChildren());

        $this->assertSame(
            [[$wrappedModel, $wrappedModel], [$wrappedModel, $wrappedModel]],
            $viewModel->getVariable('tabs')
        );
    }
}
