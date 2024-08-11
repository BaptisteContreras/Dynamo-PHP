<?php

namespace App\Background\Domain\Service\PreferenceList;

use App\Background\Domain\Exception\CannotBuildPreferenceListException;
use App\Background\Domain\Model\Aggregate\PreferenceList\PreferenceEntry;
use App\Background\Domain\Model\Aggregate\PreferenceList\PreferenceEntryCollection;
use App\Background\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Shared\Domain\Const\RingInformations;

class PreferenceListBuilder
{
    public function __construct(private readonly int $n = 3)
    {
    }

    /**
     * @throws CannotBuildPreferenceListException
     */
    public function buildFromRing(Ring $ring): PreferenceList
    {
        $virtualNodes = $ring->getVirtualNodes();

        if ($virtualNodes->isEmpty()) {
            throw new CannotBuildPreferenceListException();
        }

        $entries = [];

        $nodeRanges = $ring->getNodeSlotRanges();
        $nodeRangesSize = count($nodeRanges);

        foreach ($nodeRanges as $currentRangeIndex => $nodeRange) {
            $coordinators = [];
            $othersNodes = [];
            $currentN = 1;
            $replicaRangeIndex = ($currentRangeIndex + 1) % $nodeRangesSize;

            while ($replicaRangeIndex !== $currentRangeIndex) {
                $replicaNodeStringId = $nodeRanges[$replicaRangeIndex]->getNodeStringId();

                // skip the owner of the current range
                if ($replicaNodeStringId === $nodeRange->getNodeStringId()) {
                    $replicaRangeIndex = ($replicaRangeIndex + 1) % $nodeRangesSize;

                    continue;
                }

                if ($currentN < $this->n) {
                    if (!isset($coordinators[$replicaNodeStringId])) {
                        ++$currentN;
                    }

                    $coordinators[$replicaNodeStringId] = $nodeRanges[$replicaRangeIndex]->getNodeId();
                } elseif (!isset($coordinators[$replicaNodeStringId])) {
                    $othersNodes[$replicaNodeStringId] = $nodeRanges[$replicaRangeIndex]->getNodeId();
                }

                $replicaRangeIndex = ($replicaRangeIndex + 1) % $nodeRangesSize;
            }

            // to reset keys
            $coordinators = array_values($coordinators);
            $othersNodes = array_values($othersNodes);

            // We have now the replication infos for the whole range, lets create the entries
            $rangeDistance = ($nodeRange->getEndRange() + (RingInformations::RING_SIZE - $nodeRange->getStartRange())) % RingInformations::RING_SIZE;

            for ($i = 0; $i <= $rangeDistance; ++$i) {
                $entries[] = new PreferenceEntry(
                    ($nodeRange->getStartRange() + $i) % RingInformations::RING_SIZE,
                    $nodeRange->getNode()->getId(),
                    $coordinators,
                    $othersNodes
                );
            }
        }

        return new PreferenceList(new PreferenceEntryCollection($entries));
    }
}
