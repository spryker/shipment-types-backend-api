<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;

interface ShipmentTypesBackendApiToShipmentTypeFacadeInterface
{
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer;

    public function createShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer;

    public function updateShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer;
}
