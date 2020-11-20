<?php declare(strict_types=1);

namespace AppBundle\Normalizer;

use AppBundle\DTO\ProductDataDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CSVNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        /** @var ProductDataDTO $productDataDTO */
        $productDataDTO = new $class();

        $productDataDTO->productCode = $data['Product Code'] ?? null;
        $productDataDTO->productName = $data['Product Name'] ?? null;
        $productDataDTO->productDescription = $data['Product Description'] ?? null;
        $productDataDTO->stock = isset($data['Stock']) ? (int)$data['Stock'] : null;
        $productDataDTO->costGBP = isset($data['Cost in GBP']) ? (float)$data['Cost in GBP'] : null;
        $productDataDTO->discontinued = isset($data['Discontinued']) ? $data['Discontinued'] === 'yes' : null;

        return $productDataDTO;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return ProductDataDTO::class === $type;
    }
}