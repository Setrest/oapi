<?php

namespace Setrest\OAPIDocumentation\Router\ResponseFinders;

use Setrest\OAPIDocumentation\Router\ResponseSpec;

class ArrayFinder extends CoreResponseFinder
{
    public function find(array $returnCode, array $methodCode): ?ResponseSpec
    {
        $nonFormattedResponse = '';
        $oneLine = implode(' ', $returnCode);
        $responseSpec = new ResponseSpec();

        preg_match_all('~\(\[([\s\S]*?)\]~', $oneLine, $matches);

        if (empty($matches[1])) {
            return $this->skip($returnCode, $methodCode);
        }

        preg_match("/\d+/", str_replace($matches[1][0], '', $oneLine), $codeMatches);

        $nonFormattedResponse = explode(',', $matches[1][0]);

        foreach ($codeMatches as $match) {
            if ((int) $match < 100 && (int) $match > 1000) {
                continue;
            }
            $responseSpec->addCode((int) $match);
            break;
        }

        foreach ($nonFormattedResponse as $item) {
            $tmpResponseItem = explode('=>', trim($item))[0];
            $responseItem = trim(str_replace("'", '',$tmpResponseItem));

            $responseSpec->addProperty($responseItem);
        }

        return $responseSpec;
    }
}
