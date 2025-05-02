<?php

namespace App\Doctrine;

use App\Entity\FortuneCookie;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DiscontinuedFilter extends SQLFilter
{
    
    public function addFilterConstraint(
        ClassMetadata $targetEntity,
        $targetTableAlias
    ): string {

        if ( FortuneCookie::class !== $targetEntity->getName() ) {
            return '';
        } 

        return sprintf('%s.discontinued =%s', $targetTableAlias, $this->getParameter('discontinued'));  
        // dd($targetEntity); 
        
        // return '';
    }
}

