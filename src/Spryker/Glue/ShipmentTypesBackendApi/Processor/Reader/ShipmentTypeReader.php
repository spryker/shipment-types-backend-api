<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface;
use Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface
     */
    protected ShipmentTypeMapperInterface $shipmentTypeMapper;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface
     */
    protected ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface
     */
    protected ShipmentTypesBackendApiToShipmentTypeFacadeInterface $shipmentTypeFacade;

    public function __construct(
        ShipmentTypeMapperInterface $shipmentTypeMapper,
        ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder,
        ShipmentTypesBackendApiToShipmentTypeFacadeInterface $shipmentTypeFacade
    ) {
        $this->shipmentTypeMapper = $shipmentTypeMapper;
        $this->shipmentTypeResponseBuilder = $shipmentTypeResponseBuilder;
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    public function getShipmentTypeCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer($glueRequestTransfer);

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        return $this->shipmentTypeResponseBuilder->createShipmentTypeResponse(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
            $shipmentTypeCollectionTransfer->getPagination(),
        );
    }

    public function getShipmentType(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer($glueRequestTransfer);

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
        if ($shipmentTypeCollectionTransfer->getShipmentTypes()->count() !== 1) {
            $errorTransfers = new ArrayObject();
            $errorTransfers->append($this->shipmentTypeResponseBuilder->createEntityNotFoundErrorTransfer());

            return $this->shipmentTypeResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->shipmentTypeResponseBuilder->createShipmentTypeResponse(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
        );
    }

    protected function createShipmentTypeCriteriaTransfer(GlueRequestTransfer $glueRequestTransfer): ShipmentTypeCriteriaTransfer
    {
        $shipmentTypeCriteriaTransfer = new ShipmentTypeCriteriaTransfer();
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())->setWithStoreRelations(true);

        if ($glueRequestTransfer->getResource() && $glueRequestTransfer->getResourceOrFail()->getId()) {
            $shipmentTypeConditionsTransfer->addUuid($glueRequestTransfer->getResourceOrFail()->getIdOrFail());

            return $shipmentTypeCriteriaTransfer->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
        }

        $shipmentTypeConditionsTransfer = $this->applyShipmentTypeFilters($glueRequestTransfer, $shipmentTypeConditionsTransfer);

        return $shipmentTypeCriteriaTransfer
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
    }

    protected function applyShipmentTypeFilters(
        GlueRequestTransfer $glueRequestTransfer,
        ShipmentTypeConditionsTransfer $shipmentTypeConditionsTransfer
    ): ShipmentTypeConditionsTransfer {
        foreach ($glueRequestTransfer->getFilters() as $glueFilterTransfer) {
            if ($glueFilterTransfer->getResourceOrFail() !== ShipmentTypesBackendApiConfig::RESOURCE_SHIPMENT_TYPES) {
                continue;
            }

            $shipmentTypeConditionsTransfer = $this->shipmentTypeMapper->mapGlueFilterTransferToShipmentTypeConditionsTransfer(
                $glueFilterTransfer,
                $shipmentTypeConditionsTransfer,
            );
        }

        return $shipmentTypeConditionsTransfer;
    }
}
