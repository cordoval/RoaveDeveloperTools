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

namespace RoaveTest\DeveloperTools\Inspection;

use Roave\DeveloperTools\Inspection\TimeInspection;

/**
 * Tests for {@see \Roave\DeveloperTools\Inspection\TimeInspection}
 *
 * @covers \Roave\DeveloperTools\Inspection\TimeInspection
 * @covers \Roave\DeveloperTools\Inspection\InspectionInterface
 */
class TimeInspectionTest extends AbstractInspectionTest
{
    /**
     * {@inheritDoc}
     */
    protected function getInspection()
    {
        return new TimeInspection(123, 456);
    }

    public function testGetData()
    {
        $inspection = $this->getInspection();

        $data = $inspection->getInspectionData();

        $this->assertEquals($data, $inspection->getInspectionData(), 'Data does not mutate');
        $this->assertEquals(123, $data[TimeInspection::PARAM_START]);
        $this->assertEquals(456, $data[TimeInspection::PARAM_END]);
    }
}
