<?php

namespace App\DTO;

use Illuminate\Support\Facades\DB;

class PropertyFilterDTO
{
    public function __construct(
        public readonly ?int    $cityId            = null,
        public readonly ?int    $operationTypeId   = null,
        public readonly ?int    $propertyTypeId    = null,
        public readonly ?int    $agencyId          = null,
        public readonly ?float  $priceMin          = null,
        public readonly ?float  $priceMax          = null,
        public readonly ?float  $areaMin           = null,
        public readonly ?float  $areaMax           = null,
        public readonly ?int    $rooms             = null,
        public readonly ?string $query             = null,
        public readonly string  $sortBy            = 'latest',
        public readonly int     $perPage           = 15,
        public readonly int     $page              = 1,
    ) {}

    public static function fromRequest(array $data): self
    {
        // Use property_type_id directly (property types are stored as Configuration)
        $propertyTypeId = isset($data['property_type_id']) ? (int) $data['property_type_id'] : null;

        return new self(
            cityId:          isset($data['city_id'])            ? (int)   $data['city_id']            : null,
            operationTypeId: isset($data['operation_type_id'])  ? (int)   $data['operation_type_id']  : null,
            propertyTypeId:  $propertyTypeId,
            agencyId:        isset($data['agency_id'])          ? (int)   $data['agency_id']          : null,
            priceMin:        isset($data['price_min'])          ? (float) $data['price_min']           : null,
            priceMax:        isset($data['price_max'])          ? (float) $data['price_max']           : null,
            areaMin:         isset($data['area_min'])           ? (float) $data['area_min']            : null,
            areaMax:         isset($data['area_max'])           ? (float) $data['area_max']            : null,
            rooms:           isset($data['rooms'])              ? (int)   $data['rooms']               : null,
            query:           isset($data['q']) && $data['q'] !== '' ? (string) $data['q']             : null,
            sortBy:          $data['sort_by'] ?? 'latest',
            perPage:         isset($data['per_page']) ? min((int) $data['per_page'], 50) : 15,
            page:            isset($data['page'])    ? max(1, (int) $data['page'])        : 1,
        );
    }

    public function toQueryString(): string
    {
        $params = array_filter([
            'city_id'           => $this->cityId,
            'operation_type_id' => $this->operationTypeId,
            'property_type_id'  => $this->propertyTypeId,
            'agency_id'         => $this->agencyId,
            'price_min'         => $this->priceMin,
            'price_max'         => $this->priceMax,
            'area_min'          => $this->areaMin,
            'area_max'          => $this->areaMax,
            'rooms'             => $this->rooms,
            'q'                 => $this->query,
            'sort_by'           => $this->sortBy !== 'latest' ? $this->sortBy : null,
        ], fn($v) => $v !== null && $v !== '');

        return http_build_query($params);
    }
}
