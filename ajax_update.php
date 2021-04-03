 <?php
       $data = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
            $data = [
                'month' => (int) $this->data_get($_POST, 'name'),
                'value' => (int) trim($this->data_get($_POST, 'value')),
                'product_id' => (int) trim($this->data_get($_POST, 'pk')),
                'year' => (int) trim($this->data_get($_POST, 'year')),
                'state' => trim($this->data_get($_POST, 'state')),
                'agency_id' => $this->data_get($_POST, 'agency_id')
            ];

            if ($data['state'] === 'sale') {
                $data['number_of_sale_goods'] = $data['value'];
            }

            // if ($data['state'] === 'inventory') {
            //     $data['number_of_remaining_goods'] = $data['value'];
            // }

            $agencySale = $this->employeeSaleModel->findAgencySale($data['agency_id'], $data['product_id'], $data['month'], $data['year']);

            if ($agencySale) {
                $updateStatus = $this->employeeSaleModel->updateAgencySale($agencySale->id, $data);
            } else {
                $createStatus = $this->employeeSaleModel->createAgencySale($data['agency_id'], $data);
            }

            $this->syncYear($data);
            echo json_encode(['success' => true]);

            // if ($this->employeeSaleModel->updateOrcreateEmployeeSale($data)) {
            //     echo json_encode(['success' => true]);
            // } else {
            //     die("Something went wrong, please try again!");
            // }
        }
		?>