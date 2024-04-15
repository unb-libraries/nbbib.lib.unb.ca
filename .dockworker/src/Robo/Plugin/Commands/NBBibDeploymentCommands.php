<?php

namespace Dockworker\Robo\Plugin\Commands;

use Dockworker\DockworkerCommands;

/**
 * Defines commands to provide exceptions for NBBib deployment log errors.
 */
class NBBibDeploymentCommands extends DockworkerCommands {

  /**
   * Provides NBBib related new local ignored log exceptions.
   *
   * @hook on-event dockworker-logs-errors-exceptions
   */
  public function getNBBibErrorLogLocalExceptions() {
    return [
      [],
      array_values(
          [
            'Ignore Error related to SAPI due to field storage' => 'while adding Views handlers for field',
            'Ignore Error related to SAPI due to field storage computation' => 'while computing Views data for index',
          ]
      ),
    ];
  }

}
