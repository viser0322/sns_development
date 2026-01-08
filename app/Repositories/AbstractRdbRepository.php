<?php

namespace App\Repositories;

use Carbon\Carbon;

abstract class AbstractRdbRepository
{
  protected const FLG_ON = 1;

  protected const FLG_OFF = 0;

  protected $model;

  abstract public function getModelClass(): string;

  public function __construct()
  {
    $this->model = app($this->getModelClass());
  }

  /**
   * 指定IDのデータを返却
   */
  public function findById($id)
  {
    return $this->model->where('id', $id)->where('del_flg', self::FLG_OFF)->first();
  }

  /**
   * 全データを返却
   */
  public function findAll()
  {
    return $this->model->where('del_flg', self::FLG_OFF)->get();
  }

  /**
   * 指定IDのデータを削除
   */
  public function deleteById($id)
  {
    $now = new Carbon('now');
    $this->model->where('id', $id)->update([
      'del_flg' => self::FLG_ON,
      'deleted_at' => $now,
    ]);
  }

  /**
   * 指定IDのデータを復活
   */
  public function restoreById($id)
  {
    $this->model->where('id', $id)->update([
      'del_flg' => self::FLG_OFF,
      'deleted_at' => null,
    ]);
  }

  /**
   * 指定IDのデータを更新
   * $data = [['column' => 'value'], ['column' => 'value']...]
   */
  public function update($id, $data)
  {
    $this->model->where('id', $id)->update($data);
  }

  /**
   * 全データをカウント
   */
  public function countAll()
  {
    return $this->model->where('del_flg', self::FLG_OFF)->count();
  }
}
