{pkgs ? import <nixpkgs> {}}: let
  phpWithExtensions = pkgs.php.buildEnv {
    extensions = {
      enabled,
      all,
    }:
      enabled
      ++ (with all; [
        xdebug
        redis
      ]);
  };
  composer = phpWithExtensions.packages.composer;
in
  pkgs.mkShell {
    COREPACK_ENABLE_STRICT = 0;
    COREPACK_ENABLE_AUTO_PIN = 0;
    COREPACK_ENABLE_PROJECT_SPEC = 0;

    nativeBuildInputs = with pkgs.buildPackages; [
      jujutsu

      # PHP
      phpWithExtensions
      composer

      # Node
      nodejs_20
      corepack_20
      bun
    ];
  }
