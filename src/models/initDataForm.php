<?php

namespace sheillendra\jeasyui\models;

use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * 
 */
class InitialDataForm extends Model
{

    public $columnName = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];
    public $tableName = [
        'anggaran' => [
            'opd_id' => '', 'produk_hukum' => '', 'kode_rekening' => '',
            'jumlah' => ''
        ],
        'besaran_biaya_sppd' => [
            'pangkat_golongan_id' => '', 'eselon_id' => '',
            'jabatan_struktural_id' => '', 'jabatan_daerah_id' => '',
            'jumlah' => '', 'jenis_biaya_sppd_id' => '',
            'kategori_wilayah' => '', 'produk_hukum_id' => '', 'wilayah_id' => ''
        ],
        'eselon' => [
            'kode' => '', 'eselon' => '', 'tingkat_sppd' => '',
            'pangkat_min_id' => '', 'pangkat_max_id' => '',
        ],
        'jabatan_daerah' => [
            'id' => '', 'opd_id' => '', 'nama' => '', 'nama_2' => '',
            'tingkat_sppd' => '', 'status' => '', 'urutan' => '',
        ],
        'jabatan_keuangan' => [
            'id' => '', 'nama' => '', 'singkatan' => '', 'status' => '', 'urutan' => '',
            'keterangan' => '',
        ],
        'jabatan_struktural' => [
            'id' => '', 'opd_id' => '', 'nama' => '', 'nama_2' => '',
            'singkatan' => '', 'tingkat_sppd' => '', 'status' => '', 'urutan' => '',
        ],
        'opd' => [
            'id' => '', 'induk_id' => '', 'kode' => '', 'nama' => '', 'singkatan' => '',
            'text_kedudukan' => '', 'kode_wilayah' => '', 'baris_kop_1' => '',
            'baris_kop_2' => '', 'status' => '',
        ],
        'pegawai' => [
            'nip' => '', 'opd_id' => '', 'nama_tanpa_gelar' => '',
            'pangkat_golongan_id' => '', 'eselon_id' => '', 'status' => '',
            'gelar_depan' => '', 'gelar_belakang' => ''
        ],
        'pejabat_daerah' => [
            'jabatan_daerah_id' => '', 'nik' => '',
            'tanggal_mulai' => '', 'dasar_hukum' => '', 'status' => ''
        ],
        'pejabat_struktural' => [
            'id' => '', 'jabatan_struktural_id' => '', 'nip' => '',
            'status' => '',
        ],
        'pejabat_keuangan' => [
            'opd_id' => '', 'jabatan_keuangan_id' => '', 'pegawai_id' => '',
            'status' => ''
        ],
        'penduduk' => [
            'nik' => '', 'nama_tanpa_gelar' => '', 'jenis_kelamin' => '',
            'status' => '', 'gelar_depan' => '', 'gelar_belakang' => ''
        ],
        'pangkat_golongan' => [
            'kode' => '', 'pangkat' => '', 'golongan' => '', 'ruang' => '',
            'tingkat_sppd' => '',
        ],
        'produk_hukum' => [
            'id' => '', 'nama' => '', 'tentang' => ''
        ],
        'rekening' => [
            'kode' => '', 'nama' => '', 'kode_induk' => '', 'status' => ''
        ],
        'wilayah' => [
            'kode' => '', 'kode_kemendagri' => '', 'nama' => '',
            'kode_induk' => '', 'ibukota' => '', 'level' => '', 'kategori' => '',
        ],

    ];

    /**
     *
     * @var \yii\web\UploadedFile 
     */
    public $fileInit;
    private $_success = [];
    private $_warning = [];
    private $_error = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['fileInit', 'required'],
            ['fileInit', 'file', 'mimeTypes' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ];
    }

    public function upload()
    {
        try {
            set_time_limit(0);
            //https://github.com/PHPOffice/PhpSpreadsheet/issues/629#issuecomment-551517933
            //perbaiki dengan ini nanti
            $reader = IOFactor ::createReader('Xlsx');
            $reader->setLoadAllSheets();

            $spreadsheet = $reader->load($this->fileInit->tempName);

            foreach (self::$tableName as $tableName => $header) {
                $sheet = $spreadsheet->getSheetByName($tableName);
                if ($sheet === null) {
                    continue;
                }
                $this->process($sheet, $tableName, $header);
            }
            //$this->message();
            return true;
        } catch (\Exception $ex) {
            $this->_error[] = $ex->getMessage();
            //$this->message();
            return false;
        }
    }

    /**
     * 
     * @param \PhpOffice\PhpSpreadsheet\Helper\Sample $helper
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $spreadsheet
     * @return null
     */
    protected function process($sheet, $tableName, $header)
    {
        $className = '\common\models\\' . Inflector::id2camel($tableName . '_ext', '_');
        if (call_user_func([$className, 'find'])->count()) {
            $this->_warning[] = strtr('{table}: Data sudah ada', ['{table}' => $tableName]);
            return;
        }

        if ($this->injectData($sheet, $tableName, $header, $className)) {
            $this->_success[] = strtr('{table}: Inisialisasi data berhasil', ['{table}' => $tableName]);
        }
    }

    /**
     * 
     * @param array $header
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \PhpOffice\PhpSpreadsheet\Helper\Sample $helper
     * @return boolean
     */
    protected function columnChecker(&$header, $sheet)
    {
        $isComplete = true;
        foreach (self::$columnName as $colName) {
            $cell = $sheet->getCell($colName . '1');
            $value = $cell->getValue();
            if ($value === null) {
                break;
            }

            if (isset($header[$value])) {
                $header[$value] = $colName;
            } else {
                $this->_error[] = $value;
                $isComplete = false;
                break;
            }
        }
        return $isComplete;
    }

    protected function injectData($sheet, $tableName, $header, $classModel)
    {
        if (!$this->columnChecker($header, $sheet)) {
            $this->_error[] = strtr('{table}: Data tidak lengkap', ['{table}' => $tableName]);
            return;
        }
        $highestRow = $sheet->getHighestRow();
        $success = true;
        for ($i = 2; $i <= $highestRow; $i++) {
            /* @var $model \yii\db\ActiveRecord */
            $model = new $classModel;
            $emptyRow = true;
            foreach ($header as $colAttr => $colName) {
                if (empty($colName)) {
                    continue;
                }
                $model->{$colAttr} = trim($sheet->getCell($colName . $i)
                    ->getValue());

                if ($model->{$colAttr} === '') {
                    $model->{$colAttr} = null;
                } else {
                    $emptyRow = false;
                }
            }

            if ($emptyRow === false) {
                if ($model->save()) {
                    if (isset($header['id'])) {
                        Yii::$app->db->createCommand()->resetSequence($model->tableName())->execute();
                    }
                    $this->_success[] = $tableName . ': baris ' . $i . ' sukses';
                } else {
                    $this->_error[] = $tableName . ': ' . implode('; ', $model->getFirstErrors());
                    $success = false;
                    break;
                }
            } else {
                $this->_error[] = $tableName . ': baris ' . $i . ' kosong';
            }
        }
        return $success;
    }

    public function message()
    {
        // if ($this->_error) {
        //     Yii::$app->session->setFlash('error', implode('<br>', $this->_error));
        // }

        // if ($this->_warning) {
        //     Yii::$app->session->setFlash('warning', implode('<br>', $this->_warning));
        // }

        // if ($this->_success) {
        //     Yii::$app->session->setFlash('success', implode('<br>', $this->_success));
        // }

        return [
            'error' => $this->_error,
            'warning' => $this->_warning,
            'success' => $this->_success,
        ];
    }
}