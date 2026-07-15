{ pkgs }:

{
  deps = [
    pkgs.php83
    pkgs.php83Packages.composer

    pkgs.sqlite

    pkgs.git

    pkgs.zip
    pkgs.unzip

    pkgs.libpng
    pkgs.libjpeg
    pkgs.freetype
  ];
}