<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeMapperInterface
{
    public function mapShipmentTypeTransferToShipmentTypesBackendApiAttributesTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
    ): ShipmentTypesBackendApiAttributesTransfer;

    public function mapShipmentTypesBackendApiAttributesTransferToShipmentTypeTransfer(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ShipmentTypeTransfer;

    public function mapGlueFilterTransferToShipmentTypeConditionsTransfer(
        GlueFilterTransfer $glueFilterTransfer,
        ShipmentTypeConditionsTransfer $shipmentTypeConditionsTransfer
    ): ShipmentTypeConditionsTransfer;
}
